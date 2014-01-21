<?php

namespace CMS\FrontendBundle\Controller;

use CMS\BackendBundle\Entity\User;
use CMS\BackendBundle\Form\SignupUserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

class AuthController extends Controller
{
    public function loginAction($render)
    {
        if ($this->get('request')->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $this->get('request')->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $this->get('request')->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }
        if ($render) {
            return $this->render('CMSFrontendBundle:Auth:login.html.twig', array(
                'last_username' => $this->get('request')->getSession()->get(SecurityContext::LAST_USERNAME),
                'error' => $error
            ));
        } else {
            return $this->render('CMSFrontendBundle:Auth:loginPage.html.twig', array(
                'last_username' => $this->get('request')->getSession()->get(SecurityContext::LAST_USERNAME),
                'error' => $error
            ));
        }
    }

    public function registerAction()
    {
        $registerForm = $this->createForm(new SignupUserType());
        return $this->render('CMSFrontendBundle:Auth:signup.html.twig', array('signup' => $registerForm->createView()));
    }

    public function addUserAction(Request $request)
    {
        $signupForm = $this->createForm(new SignupUserType());
        $passwords = $request->request->get($signupForm->getName())['password'];
        if ($passwords['first'] != $passwords['second']) {
            $this->get('session')->getFlashBag()->add('notice', 'Введённые вами пароли не совпадают.');
            return $this->redirect($this->generateUrl('index_page'));
        }
        $signupForm->submit($request);
        if ($signupForm->isValid()) {
            $em = $this->get('doctrine')->getManager();
            $user = $signupForm->getData();
            if ($em->getRepository('CMSBackendBundle:User')->findOneBy(array('username' => $user->getUsername()))) {
                $this->get('session')->getFlashBag()->add('notice', 'Пользователь с таким именем существует.');
                return $this->redirect($this->generateUrl('index_page'));
            }
            $user->setSalt(md5(time()));
            var_dump($passwords);exit;
            $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
            $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
            $user->setPassword($password);
            $user->getUserRoles()->add($em->getRepository('CMSBackendBundle:UserRoles')->findOneBy(array('id' => 1)));
            try {
                $em->persist($user);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'Пользователь успешно зарегистрирован.');
                return $this->redirect($this->generateUrl('index_page'));
            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('error', 'Ошибка работы с базой.');
                return $this->redirect($this->generateUrl('index_page'));
            }
        } else {
            $this->get('cms.helper')->invalidNotice($signupForm);
            return $this->redirect($this->generateUrl('index_page'));
        }

    }
}
