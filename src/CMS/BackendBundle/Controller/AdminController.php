<?php

namespace CMS\BackendBundle\Controller;

use CMS\BackendBundle\Form\CategoriesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{

    public function userListAction()
    {
        if (! $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            return $this->redirect($this->generateUrl('_security_login'));
        }
        $em = $this->get('doctrine')->getManager();
        $users = $em->getRepository('CMSBackendBundle:User')->findAllUsers();

        $pagination = $this->get('knp_paginator')->paginate(
            $users, $this->get('request')->query->get('page', 1), 25
        );
        return $this->render('CMSBackendBundle:Admin:userList.html.twig', array(
            'users' => $pagination,
        ));
    }

    public function userSearchAction()
    {
        if (! $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            return $this->redirect($this->generateUrl('_security_login'));
        }
        $request = $this->get('request')->request;
        if ($request->has('username') || $request->get('username')) {
            $em = $this->get('doctrine')->getManager();
            $users = $em->getRepository('CMSBackendBundle:User')->searchUser($request->get('username'));

            return $this->render('CMSBackendBundle:Admin:userList.html.twig', array(
                'searchString' => $request->get('username'),
                'users' => $users,
            ));
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Неверные параметры поиска.');
            return $this->redirect($this->generateUrl('_admin_users'));
        }
    }

    public function categorySearchAction()
    {
        if (! $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            return $this->redirect($this->generateUrl('_security_login'));
        }
        $request = $this->get('request')->request;
        if ($request->has('category') || $request->get('category')) {
            $em = $this->get('doctrine')->getManager();
            $categories = $em->getRepository('CMSBackendBundle:Categories')->searchCategory($request->get('category'));

            return $this->render('CMSBackendBundle:Admin:categoryList.html.twig', array(
                'searchString' => $request->get('category'),
                'categories' => $categories,
            ));
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Неверные параметры поиска.');
            return $this->redirect($this->generateUrl('_admin_categories'));
        }
    }

    public function categoryListAction()
    {
        if (! $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            return $this->redirect($this->generateUrl('_security_login'));
        }
        $em = $this->get('doctrine')->getManager();
        $categories = $em->getRepository('CMSBackendBundle:Categories')->childrenHierarchy();

        return $this->render('CMSBackendBundle:Admin:categoryList.html.twig', array('categories' => $categories));
    }

    public function categoryFormAction($categoryID)
    {
        if (! $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            return $this->redirect($this->generateUrl('_security_login'));
        }
        if ($categoryID) {
            $em = $this->get('doctrine')->getManager();
            $category = $em->getRepository('CMSBackendBundle:Categories')->findOneById($categoryID);
            if ($category) {
                $categoryForm = $this->createForm(new CategoriesType(), $category);
            } else {
                $this->get('session')->getFlashBag()->add('error', 'Категирии с таким номером не существует!');
                return $this->redirect($this->generateUrl('_admin_categories'));
            }
        } else {
            $categoryForm = $this->createForm(new CategoriesType());
        }

        return $this->render('CMSBackendBundle:Admin:categoryForm.html.twig', array(
            'categoryID' => $categoryID,
            'categoryForm' => $categoryForm->createView(),
        ));
    }

    public function categoryDeleteAction($categoryID)
    {
        if (! $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            return $this->redirect($this->generateUrl('_security_login'));
        }
        $em = $this->get('doctrine')->getManager();
        $category = $em->getRepository('CMSBackendBundle:Categories')->findOneById($categoryID);

        if ($category) {
            try {
                $em->remove($category);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'Категирия успешно удалена.');
                return $this->redirect($this->generateUrl('_admin_categories'));
            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('error', 'Ошибка работы с базой.');
                return $this->redirect($this->generateUrl('profile'));
            }
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Категирии с таким номером не существует!');
            return $this->redirect($this->generateUrl('_admin_categories'));
        }

    }

    public function categorySaveAction($categoryID = null)
    {
        if (! $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            return $this->redirect($this->generateUrl('_security_login'));
        }
        $request = $this->get('request');
        $em = $this->get('doctrine')->getManager();
        if ($categoryID) {
            $category = $em->getRepository('CMSBackendBundle:Categories')->findOneById($categoryID);
            if ($category) {
                $categoryForm = $this->createForm(new CategoriesType(), $category);
            } else {
                $this->get('session')->getFlashBag()->add('error', 'Категирии с таким номером не существует!');
                return $this->redirect($this->generateUrl('_admin_categories'));
            }
        } else {
            $categoryForm = $this->createForm(new CategoriesType());
        }
        if ($request->getMethod() == 'POST') {
            $categoryForm->submit($request);
            try {
                $category = $categoryForm->getData();
                $category->setAlias($this->get('cms.helper')->translitIt($category->getTitle()));
                $em->persist($category);
                $em->flush();
                if ($categoryID && $request->headers->get('referer')) {
                    $this->get('session')->getFlashBag()->add('success', 'Категория успешно изменена.');
                    return $this->redirect($request->headers->get('referer'));
                } else {
                    $this->get('session')->getFlashBag()->add('success', 'Категория успешно добавлена.');
                    return $this->redirect($this->generateUrl('_admin_categories'));
                }
            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('error', 'Ошибка работы с базой.');
                return $this->redirect($this->generateUrl('profile'));
            }
        }
    }

}
