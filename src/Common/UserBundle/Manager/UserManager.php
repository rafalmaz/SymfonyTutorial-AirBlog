<?php

namespace Common\UserBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface as Templating;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Common\UserBundle\Mailer\UserMailer;
use Common\UserBundle\Entity\User;
use Common\UserBundle\Exception\UserException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserManager
{
    /**
     * @var Doctrine
     */
    protected $doctrine;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var Templating
     */
    protected $templating;

    /**
     * @var EncoderFactory
     */
    protected $encoderFactory;

    /**
     * @var UserMailer
     */
    protected $userMailer;


    function __construct(Doctrine $doctrine, Router $router, Templating $templating, EncoderFactory $encoderFactory, UserMailer $userMailer) {
        $this->doctrine = $doctrine;
        $this->router = $router;
        $this->templating = $templating;
        $this->encoderFactory = $encoderFactory;
        $this->userMailer = $userMailer;
    }

    public function sendResetPasswordLink($userEmail) {
        $User = $this->doctrine->getRepository('CommonUserBundle::User')->findOneByEmail($userEmail);

        if($User === null) {
            throw new UserExeption('Nie znaleziono takiego użytkownika');
        }

        $User->getActionToken($this->generateActionToken());

        $em = $this->doctrine->getManager();
        $em->persist($User);
        $em->flush();

        $urlParams = array('actionToken' => $User->getActionToken());
        $resetUrl = $this->router->generate('user_resetPassword', $urlParams, UrlGeneratorInterface::ABSOLUTE_URL);

        $emailBody = $this->templating->render('CommonUserBundle:Email:passwordResetLink.html.twig', array('resetUrl' => $resetUrl));

        $this->userMailer->send($User, 'Link resetujący hasło', $emailBody);
    }

    protected function generateActionToken() {
        return substr(md5(uniqid(NULL, TRUE)), 0, 20);
    }
}