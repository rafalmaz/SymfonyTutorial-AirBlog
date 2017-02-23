<?php

namespace Air\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class PagesController extends Controller
{
    /**
     * @Route(
     *     "/about",
     *     name = "blog_about"
     * )
     * @Template()
     */
    public function aboutAction(){
        return array();
        //return $this->render('AirBlogBundle:Pages:about.html.twig');
    }

    /**
     * @Route(
     *     "/contact",
     *     name = "blog_contact"
     * )
     * @Template()
     */
    public function contactAction() {
        return array();
    }

}
