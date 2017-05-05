<?php

namespace Common\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Common\UserBundle\Form\Type\LoginType;

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


        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->render('CommonUserBundle:Login:login.html.twig', array(
                'loginError' => $error,
                'loginForm' => $loginForm->createView()
            ));
        }
        else {
            return $this->redirect($this->generateUrl('/login'));
        }



    }
}
