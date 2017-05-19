<?php

namespace Common\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Common\UserBundle\Form\Type\AccountSettingsType;
use Common\UserBundle\Form\Type\ChangePasswordType;

use Common\UserBundle\Exception\UserException;

class LoginController extends Controller
{

    /**
     * @Route("/login", name="blog_login")
     */
    public function loginAction(Request $Request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $loginForm = $this->createForm(new LoginType(), array(
            'username' => $lastUsername
        ));

        $rememberPasswordForm = $this->createForm(new RememberPasswordType());

        if($Request->isMethod('POST')) {
            $rememberPasswordForm->handleRequest($Request);

            if($rememberPasswordForm->isValid()) {
                try {
                    $userEmail = $rememberPasswordForm->get('email')->getData();
                    $userManager = $this->get('user_manager');
                    $userManager->sendResetPasswordLink($userEmail);
                    $this->get('session')->getFlashBag()->add('success', 'Instrukcje resetowania hasła zostały wysłane na adres email');
                    return $this->redirect($this->generateUrl('blog_login'));
                }
                catch (UserExeption $exec) {
                    $error = new FromError($exec->getMessage());
                    $rememberPasswordForm->get('email')->addError($error);
                }
            }
        }


        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->render('CommonUserBundle:Login:login.html.twig', array(
                'loginError' => $error,
                'loginForm' => $loginForm->createView(),
                'rememberPasswordForm' => $rememberPasswordForm->createView()
            ));
        }
        else {
            return $this->redirect($this->generateUrl('/login'));
        }



    }
}
