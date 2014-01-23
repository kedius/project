<?php

namespace CMS\BackendBundle\Controller;

use CMS\BackendBundle\Entity\Content;
use CMS\BackendBundle\Entity\ContentReview;
use CMS\BackendBundle\Entity\Files;
use CMS\BackendBundle\Entity\Tags;
use CMS\BackendBundle\Form\AdminUserType;
use CMS\BackendBundle\Form\ContentReviewType;
use CMS\BackendBundle\Form\ContentType;
use CMS\BackendBundle\Form\UserType;
use CMS\BackendBundle\Helper\KeeperDataTable;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class CustomController extends Controller
{

    public function profileAction()
    {
        if (! $this->get('security.context')->isGranted('ROLE_USER')) {
	    return $this->redirect($this->generateUrl('_security_login'));
	}
        return $this->render('CMSBackendBundle:Custom:profile.html.twig');
    }

    public function editProfileAction($username = null)
    {
        if ($username && $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $em = $this->get('doctrine')->getManager();
            $user = $em->getRepository('CMSBackendBundle:User')->getUserByUsername($username);
            if ($user) {
                $userForm = $this->createForm(new AdminUserType(), $user);
            } else {
                $this->get('session')->getFlashBag()->add('error', 'Пользователя с таким логином не существует!');
                return $this->redirect($this->generateUrl('_admin_users'));
            }
        } else if (! $username && $this->get('security.context')->isGranted('ROLE_USER')) {
            $userForm = $this->createForm(new UserType(), $this->getUser());
        } else {
            return $this->redirect($this->generateUrl('_security_login'));
        }
        return $this->render('CMSBackendBundle:Custom:editProfile.html.twig', array(
            'username' => $username,
            'userForm' => $userForm->createView(),
        ));
    }

    public function saveProfileAction($username)
    {
        $request = $this->get('request');
        $em = $this->get('doctrine')->getManager();
        if ($request->getMethod() == 'POST') {
            if ($username && $this->get('security.context')->isGranted('ROLE_ADMIN')) {
                $user = $em->getRepository('CMSBackendBundle:User')->getUserByUsername($username);
                $userForm = $this->createForm(new AdminUserType(), $user);
                $userImg = $user->getImage();
            } else if (! $username && $this->get('security.context')->isGranted('ROLE_USER')) {
                $userForm = $this->createForm(new UserType(), $this->getUser());
                $userImg = $this->getUser()->getImage();
            } else {
                return $this->redirect($this->generateUrl('_security_login'));
            }
            $userForm->submit($request);
            if ($userForm->isValid()) {
                $user = $userForm->getData();
                if ($user->getImage() instanceof UploadedFile) {
                    $file = $this->get('cms.helper')->uploadFile($user->getImage(), $user->getUsername().'/avatar');
                    $user->setImage($file['path'].'/'.$file['name']);
                    if ($userImg != null) {
		        if (file_exists($userImg)) {
                            unlink($userImg);
		        }
                    }
                } else {
                    $user->setImage($userImg);
                }
                try {
                    $em->persist($user);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'Данные пользователя успешно изменены.');
                    if (! $username && $request->headers->get('referer')) {
                        return $this->redirect($request->headers->get('referer'));
                    } else {
                        return $this->redirect($this->generateUrl('_admin_users'));
                    }
                } catch (\Exception $e) {
                    $this->get('session')->getFlashBag()->add('error', 'Ошибка работы с базой.');
                    return $this->redirect($this->generateUrl('profile'));
                }
            } else {
                $this->get('cms.helper')->invalidNotice($userForm);
                if (! $username && $request->headers->get('referer')) {
                    return $this->redirect($request->headers->get('referer'));
                } else {
                    return $this->redirect($this->generateUrl('_admin_users'));
                }
            }
        }
    }

    public function addContentAction($contentAlias = null)
    {
        if ( ! $this->get('security.context')->isGranted('ROLE_AUTHOR')) {
            return $this->redirect($this->generateUrl('_security_login'));
        }
        if ($contentAlias) {
            $em = $this->get('doctrine')->getManager();
            $content = $em->getRepository('CMSBackendBundle:Content')->getContentByAlias($contentAlias, KeeperDataTable::STATUS_NOT_PUBLISHED);
            if ($content instanceof Content) {
                if ($content->getAuthor() != $this->getUser()) {
                    $this->get('session')->getFlashBag()->add('notice', 'Вы не являетесь автором этой публикации.');
                    return $this->redirect($this->generateUrl('profile_my_content'));
                }
                $options['content'] = $content;
                $options['contentForm'] = $this->createForm(new ContentType(), $content)->createView();
            } else {
                $this->get('session')->getFlashBag()->add('notice', 'Публикация не существует либо она уже обработана и не может быть изменена.');
                return $this->redirect($this->generateUrl('profile_my_content'));
            }
        } else {
            $options['contentForm'] = $this->createForm(new ContentType())->createView();
        }

        return $this->render('CMSBackendBundle:Custom:contentForm.html.twig', $options);

    }

    public function saveContentAction()
    {
        if ( ! $this->get('security.context')->isGranted('ROLE_AUTHOR')) {
            return $this->redirect($this->generateUrl('_security_login'));
        }
        $request = $this->get('request');
        $session = $this->get('session');
        if ($request->getMethod() == 'POST') {
            $em = $this->get('doctrine')->getManager();
            $contentForm = $this->createForm(new ContentType());
            $contentForm->submit($request);
            if ($contentForm->isValid()) {
                $content = $contentForm->getData();
                $content->setAlias($this->get('cms.helper')->translitIt($content->getTitle()));
                $content->setStatus($em->getRepository('CMSBackendBundle:Statuses')->findOneById(KeeperDataTable::STATUS_NOT_PUBLISHED));
                $content->setAuthor($this->getUser());
                if (empty($content->getFiles())) {
                    $content->setContentType($em->getRepository('CMSBackendBundle:ContentTypes')->findOneById(KeeperDataTable::CONTENT_TYPE_ARTICLE));
                } else {
                    $content->setContentType($em->getRepository('CMSBackendBundle:ContentTypes')->findOneById(KeeperDataTable::CONTENT_TYPE_FILE));
                    foreach($content->getFiles() as $file) {
                        $file->setContent($content);
                        $upload = $this->get('cms.helper')->uploadFile($file->getFile(), $this->getUser()->getUsername().'/content/'.$content->getDateAt()->format("d-m-Y_H-i-s"), true);
                        $file->setFileName($upload['name']);
                        $file->setFilePath($upload['path']);
                        $file->setMimeType($upload['type']);
                    }
                }
                if ($request->request->get('contentTags') != '') {
                    $tags = explode(',', $request->request->get('contentTags'));
                    foreach ($tags as $tagID) {
                        if ((int) $tagID && $tag = $em->getRepository('CMSBackendBundle:Tags')->findOneById($tagID)) {
                            $content->addTags($tag);
                        } else {
                            $tag = new Tags();
                            $tag->setName($tagID);
                            $content->addTags($tag);
                        }
                    }
                }
                try {
                    $em->persist($content);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'Публикация успешно добавленна. Ожидайте ответа издателя.');
                    return $this->redirect($this->generateUrl('index_page'));
                } catch (\Exception $e) {
                    $this->get('session')->getFlashBag()->add('error', 'Ошибка работы с базой.');
                    return $this->redirect($this->generateUrl('profile'));
                }
            } else {
                $this->get('cms.helper')->invalidNotice($contentForm);
                return $this->redirect($this->generateUrl('add_profile_content'));
            }
        }
    }

    public function removeFileAjaxAction()
    {
        $request = $this->get('request')->request;
        if ($request->has('fileID')) {
            $em = $this->get('doctrine')->getManager();
            $file = $em->getRepository('CMSBackendBundle:Files')->findOneById($request->get('fileID'));
            if ($file instanceof Files) {
                $filePath = $file->getFilePath().'/'.$file->getFileName();
                try {
                    $em->remove($file);
                    $em->flush();
                } catch (\Exception $e) {
                    return new Response(json_encode(array('status' => false, 'message' => 'Ошибка работы с базой.')), 500, array('Content-Type' => 'application/json'));
                }
		if (file_exists($filePath)) {
                    unlink($filePath);
		}
                return new Response(json_encode(array('status' => true, 'message' => 'Файл успешно удалён.')), 200, array('Content-Type' => 'application/json'));
            } else {
                return new Response(json_encode(array('status' => false, 'message' => 'Файл не существует.')), 500, array('Content-Type' => 'application/json'));
            }
        }
    }

    public function editSaveContentAction($contentAlias)
    {
        if ( ! $this->get('security.context')->isGranted('ROLE_AUTHOR')) {
            return $this->redirect($this->generateUrl('_security_login'));
        }
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $em = $this->get('doctrine')->getManager();
            $content = $em->getRepository('CMSBackendBundle:Content')->getContentByAlias($contentAlias, KeeperDataTable::STATUS_NOT_PUBLISHED);
            if ($content instanceof Content) {
                if ($content->getAuthor() != $this->getUser()) {
                    $this->get('session')->getFlashBag()->add('notice', 'Вы не являетесь автором этой публикации.');
                    return $this->redirect($this->generateUrl('profile_my_content'));
                }
                $contentForm = $this->createForm(new ContentType(), $content);
                $contentForm->submit($request);
                if ($contentForm->isValid()) {
                    $content = $contentForm->getData();
                    if (!empty($content->getFiles())) {
                        foreach($content->getFiles() as $file) {
                            $upload = $this->get('cms.helper')->uploadFile($file->getFile(), $this->getUser()->getUsername().'/content/'.$content->getDateAt()->format("d-m-Y_H-i-s"), true);
                            $newFile = new Files();
                            $newFile->setFileName($upload['name']);
                            $newFile->setFilePath($upload['path']);
                            $newFile->setMimeType($upload['type']);
                            $newFile->setContent($content);
                            $content->getFiles()->add($newFile);
                        }
                    }
                    $content->checkFiles();
                    if ($request->request->get('contentTags') != '') {
                        $tags = explode(',', $request->request->get('contentTags'));
                        foreach ($tags as $tagID) {
                            if ((int) $tagID && $tag = $em->getRepository('CMSBackendBundle:Tags')->findOneById($tagID)) {
                                $content->addTags($tag);
                            } else {
                                $tag = new Tags();
                                $tag->setName($tagID);
                                $content->addTags($tag);
                            }
                        }
                    }
                    try {
                        $em->persist($content);
                        $em->flush();
                        $this->get('session')->getFlashBag()->add('success', 'Публикация успешно изменена.');
                        return $this->redirect($this->generateUrl('profile_my_content'));
                    } catch (\Exception $e) {
                        $this->get('session')->getFlashBag()->add('error', 'Ошибка работы с базой.');
                        return $this->redirect($this->generateUrl('profile'));
                    }
                } else {
                    $this->get('cms.helper')->invalidNotice($contentForm);
                    return $this->redirect($this->generateUrl('edit_profile_content', array('contentAlias' => $contentAlias)));
                }
            } else {
                $this->get('session')->getFlashBag()->add('notice', 'Публикация не существует либо она уже обработана и не может быть изменена.');
                return $this->redirect($this->generateUrl('profile_my_content'));
            }
        }
    }

    public function contentNotPublishedListAction()
    {
        if ( ! $this->get('security.context')->isGranted('ROLE_PUBLISHER')) {
            return $this->redirect($this->generateUrl('_security_login'));
        }
        $em = $this->get('doctrine')->getManager();
        $contents = $em->getRepository('CMSBackendBundle:Content')->findAllContent(KeeperDataTable::STATUS_NOT_PUBLISHED);

        return $this->render('CMSBackendBundle:Custom:contentList.html.twig', array(
            'contents' => $contents,
        ));
    }

    public function contentNotPublishedByCategoryAction($category)
    {
        if ( ! $this->get('security.context')->isGranted('ROLE_PUBLISHER')) {
            return $this->redirect($this->generateUrl('_security_login'));
        }
        $em = $this->get('doctrine')->getManager();
        $options['contents'] = $em->getRepository('CMSBackendBundle:Content')->findAllContentByCategory($category, KeeperDataTable::STATUS_NOT_PUBLISHED);
        if (count($options['contents']) > 0) {
            $options['breadCrumbs'] = $em->getRepository('CMSBackendBundle:Categories')->getPath(current($options['contents'])->getCategory());
        }

        return $this->render('CMSBackendBundle:Custom:contentList.html.twig', $options);
    }

    public function contentMyReviewListAction()
    {
        if ( ! $this->get('security.context')->isGranted('ROLE_PUBLISHER')) {
            return $this->redirect($this->generateUrl('_security_login'));
        }
        $em = $this->get('doctrine')->getManager();
        $contents = $em->getRepository('CMSBackendBundle:Content')->getMyReviewContent($this->getUser()->getId());
        return $this->render('CMSBackendBundle:Custom:contentList.html.twig', array(
            'myreview' => true,
            'contents' => $contents,
        ));
    }


    public function reviewedContentAction($contentAlias)
    {
        if ( ! $this->get('security.context')->isGranted('ROLE_PUBLISHER')) {
            return $this->redirect($this->generateUrl('_security_login'));
        }
        $em = $this->get('doctrine')->getManager();
        $content = $em->getRepository('CMSBackendBundle:Content')->getContentByAlias($contentAlias, KeeperDataTable::STATUS_NOT_PUBLISHED);
        $contentReviewForm = $this->createForm(new ContentReviewType());
        $breadCrumbs = $em->getRepository('CMSBackendBundle:Categories')->getPath($content->getCategory());

        return $this->render('CMSBackendBundle:Custom:contentReview.html.twig', array(
            'content' => $content,
            'breadCrumbs' => $breadCrumbs,
            'reviewForm' => $contentReviewForm->createView()
        ));
    }

    public function saveReviewContentAction($contentAlias)
    {
        if ( ! $this->get('security.context')->isGranted('ROLE_PUBLISHER')) {
            return $this->redirect($this->generateUrl('_security_login'));
        }
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $em = $this->get('doctrine')->getManager();
            $content = $em->getRepository('CMSBackendBundle:Content')->getContentByAlias($contentAlias, KeeperDataTable::STATUS_NOT_PUBLISHED);
            $contentReviewForm = $this->createForm(new ContentReviewType(), $content);
            $contentReviewForm->submit($request);
            if ($contentReviewForm->isValid()) {
                $content = $contentReviewForm->getData();
                $review = new ContentReview();
                $review->setComment($content->getReview());
                $review->setContent($content);
                $content->setReview($review);
                if ($request->request->get('reviewTags') != '') {
                    $tags = explode(',', $request->request->get('reviewTags'));
                    foreach ($tags as $tagID) {
                        if ((int) $tagID && $tag = $em->getRepository('CMSBackendBundle:Tags')->findOneById($tagID)) {
                            $content->addTags($tag);
                        } else {
                            $tag = new Tags();
                            $tag->setName($tagID);
                            $content->addTags($tag);
                        }
                    }
                }
                $content->setPublisher($this->getUser());
                $content->setPublicDate(new \DateTime());
                try {
                    $em->persist($content);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'Материал успешно обработан.');
                    return $this->redirect($this->generateUrl('profile_content'));
                } catch (\Exception $e) {
                    $this->get('session')->getFlashBag()->add('error', 'Ошибка работы с базой.');
                    return $this->redirect($this->generateUrl('profile_my_content'));
                }
            } else {
                $this->get('cms.helper')->invalidNotice($contentReviewForm);
                return $this->redirect($this->generateUrl('profile_content_reviewed', array('contentAlias' => $contentAlias)));
            }
        }
    }

    public function myContentAction($status = null)
    {
        if ( ! $this->get('security.context')->isGranted('ROLE_AUTHOR')) {
            return $this->redirect($this->generateUrl('_security_login'));
        }
        $em = $this->get('doctrine')->getManager();
        $myContents = $em->getRepository('CMSBackendBundle:Content')->getMyContents($this->getUser()->getId(), $status);

        $pagination = $this->get('knp_paginator')->paginate(
            $myContents, $this->get('request')->query->get('page', 1), 25
        );

        return $this->render('CMSBackendBundle:Custom:myContentList.html.twig', array(
            'status' => $status,
            'myContents' => $pagination,
        ));
    }

    public function viewAnyContentsAction($contentAlias)
    {
        if ($this->get('security.context')->isGranted('ROLE_AUTHOR') || $this->get('security.context')->isGranted('ROLE_PUBLISHER') ) {
            $em = $this->get('doctrine')->getManager();
            $content = $em->getRepository('CMSBackendBundle:Content')->getContentByAlias($contentAlias);
            if ($content) {
                if ($this->get('security.context')->isGranted('ROLE_AUTHOR') && $content->getAuthor()->getId() != $this->getUser()->getId()) {
                    $this->get('session')->getFlashBag()->add('notice', 'Вы не являетесь автором данного материала.');
                    return $this->redirect($this->generateUrl('profile_my_content'));
                }
                return $this->render('CMSBackendBundle:Custom:viewAnyContent.html.twig', array(
                    'content' => $content,
                ));
            } else {
                $this->get('session')->getFlashBag()->add('notice', 'Запрашиваемый материал отсутствует.');
                return $this->redirect($this->generateUrl('profile_my_content'));
            }
        } else {
            $this->get('session')->getFlashBag()->add('notice', 'Просматривать публикации могут только пользователи группы "Автор" либо "Издатель".');
            if ($this->get('request')->headers->has('referer')) {
                return $this->redirect($this->get('request')->headers->get('referer'));
            } else {
                return $this->redirect($this->generateUrl('index_page'));
            }
        }
    }
}
