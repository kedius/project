<?php

namespace CMS\FrontendBundle\Controller;

use CMS\FrontendBundle\Form\CommentsType;
use CMS\BackendBundle\Helper\KeeperDataTable;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class MainController extends Controller
{

    public function indexAction()
    {
        $em = $this->get('doctrine')->getManager();
        $content = $em->getRepository('CMSBackendBundle:Content')->findAllContent(KeeperDataTable::STATUS_PUBLISHED);
        $pagination = $this->get('knp_paginator')->paginate(
            $content, $this->get('request')->query->get('page', 1), 25
        );
        return $this->render('CMSFrontendBundle:Main:index.html.twig', array('pagination' => $pagination));
    }

    public function contentByTagAction($tag)
    {
        $em = $this->get('doctrine')->getManager();
        $content = $em->getRepository('CMSBackendBundle:Content')->findAllContentByTag($tag, KeeperDataTable::STATUS_PUBLISHED);
        $pagination = $this->get('knp_paginator')->paginate(
            $content, $this->get('request')->query->get('page', 1), 25
        );
        return $this->render('CMSFrontendBundle:Main:index.html.twig', array('pagination' => $pagination));
    }

    public function contentByCategoryAction($category)
    {
        $em = $this->get('doctrine')->getManager();
        $content = $em->getRepository('CMSBackendBundle:Content')->findAllContentByCategory($category, KeeperDataTable::STATUS_PUBLISHED);
        if (count($content) > 0) {
            $options['breadCrumbs'] = $em->getRepository('CMSBackendBundle:Categories')->getPath(current($content)->getCategory());
        }
        $options['pagination'] = $this->get('knp_paginator')->paginate(
            $content, $this->get('request')->query->get('page', 1), 25
        );
        return $this->render('CMSFrontendBundle:Main:index.html.twig', $options);
    }

    public function contentByUserAction($username)
    {
        $em = $this->get('doctrine')->getManager();
        $content = $em->getRepository('CMSBackendBundle:Content')->findAllContentByUser($username, KeeperDataTable::STATUS_PUBLISHED);
        $pagination = $this->get('knp_paginator')->paginate(
            $content, $this->get('request')->query->get('page', 1), 25
        );
        return $this->render('CMSFrontendBundle:Main:index.html.twig', array('pagination' => $pagination));
    }

    public function viewContentAction($alias)
    {
        $em = $this->get('doctrine')->getManager();
        $content = $em->getRepository('CMSBackendBundle:Content')->getContentByAlias($alias, KeeperDataTable::STATUS_PUBLISHED);
        if (! $content) {
            $this->get('session')->getFlashBag()->add('error', 'Публикация не найдена!');
            return $this->redirect($this->generateUrl('index_page'));
        }
        $breadCrumbs = $em->getRepository('CMSBackendBundle:Categories')->getPath($content->getCategory());
        return $this->render('CMSFrontendBundle:Main:viewContent.html.twig', array(
            'breadCrumbs' => $breadCrumbs,
            'content' => $content
        ));
    }

    public function commentsAction($alias)
    {
        $commentsForm = $this->createForm(new CommentsType());
        return $this->render('CMSFrontendBundle:Main:comments.html.twig', array(
            'contentAlias' => $alias,
            'commentsForm' => $commentsForm->createView()
        ));
    }

    public function addCommentAction($alias)
    {
        $request = $this->get('request');
        $commentsForm = $this->createForm(new CommentsType());
        if ($request->getMethod() == 'POST') {
            $em = $this->get('doctrine')->getManager();
            $commentsForm->submit($request);
            if ($commentsForm->isValid()) {
                $content = $em->getRepository('CMSBackendBundle:Content')->getContentByAlias($alias, KeeperDataTable::STATUS_PUBLISHED);
                if ($content) {
                    $comment = $commentsForm->getData();
                    $comment->setUser($this->getUser());
                    $comment->setContent($content);
                    try {
                        $em->persist($comment);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('success', 'Комментарий успешно добавлен.');
                        if ($request->headers->get('referer')) {
                            return $this->redirect($request->headers->get('referer').'#comments');
                        } else {
                            return $this->redirect($this->generateUrl('index_page'));
                        }
                    } catch (\Exception $e) {
                        $this->get('session')->getFlashBag()->add('error', 'Ошибка работы с базой.');
                        return $this->redirect($this->generateUrl('index_page'));
                    }
                }
            } else {
                $this->get('cms.helper')->invalidNotice($commentsForm);
                return $this->redirect($this->generateUrl('view_content', array('alias', $alias)).'#comments');
            }
        }
    }

    public function categoriesAction($path)
    {
        $em = $this->get('doctrine')->getManager();
        $options = array(
            'decorate' => true,
            'nodeDecorator' => function($node) use ($path) {
                return '<div class="category">'.str_repeat('---', $node['level']).' <a href="'.$this->generateUrl($path, array('category' => $node['alias'])).'">'.$node['title'].'</a></div>';
            }
        );
        $categories = $em->getRepository('CMSBackendBundle:Categories')->childrenHierarchy(null, false, $options);
        return $this->render('CMSFrontendBundle:Main:categories.html.twig', array('categories' => $categories));
    }

    public function tagsAction()
    {
        $em = $this->get('doctrine')->getManager();
        $tags = $em->getRepository('CMSBackendBundle:Tags')->getAllTagsWithCount();
        return $this->render('CMSFrontendBundle:Main:tags.html.twig', array('tags' => $tags));
    }

    public function ajaxRateAction()
    {
        $request = $this->get('request')->request;
        if ($request->has('type') && $request->has('id') && $request->has('rate')) {
            $em = $this->get('doctrine')->getManager();
            if ($request->get('type') == 'content' && $ratedEntity = $em->getRepository('CMSBackendBundle:Content')->findOneById($request->get('id'))) {
                $ratedEntity->setRating($request->get('rate') == 'up' ? $ratedEntity->getRating()+1 : $ratedEntity->getRating()-1);
            } else if ($request->get('type') == 'comment' && $ratedEntity = $em->getRepository('CMSBackendBundle:Comments')->findOneById($request->get('id'))) {
                $ratedEntity->setRating($request->get('rate') == 'up' ? $ratedEntity->getRating()+1 : $ratedEntity->getRating()-1);
            } else {
                return new Response(json_encode(array('status' => false, 'message' => 'Неверный идентификатор!')), 500, array('Content-Type' => 'application/json'));
            }
            try {
                $em->persist($ratedEntity);
                $em->flush();
            } catch (\Exception $e) {
                return new Response(json_encode(array('status' => false, 'message' => 'Ошибка работы с базой!')), 500, array('Content-Type' => 'application/json'));
            }
            $session = $this->get('session');
            $rated = $session->get('rated');
            if (isset($rated[$request->get('type')][$request->get('id')]['rate'])) {
                if (($rated[$request->get('type')][$request->get('id')]['rate'] == 'up' && $request->get('rate') == 'down') || ($rated[$request->get('type')][$request->get('id')]['rate'] == 'down' && $request->get('rate') == 'up')) {
                    unset($rated[$request->get('type')][$request->get('id')]);
                }
            } else {
                $rated[$request->get('type')][$request->get('id')]['rate'] = $request->get('rate');
            }
            $session->set('rated', $rated);
            return new Response(json_encode(array('status' => true, 'message' => 'Ваш голос учтён.')), 200, array('Content-Type' => 'application/json'));
        } else {
            return new Response(json_encode(array('status' => false, 'message' => 'Неверные параметры запроса!')), 500, array('Content-Type' => 'application/json'));
        }
    }

    public function loggedAction()
    {
        return $this->render('CMSFrontendBundle:Main:logged.html.twig');
    }


}
