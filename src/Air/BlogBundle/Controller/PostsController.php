<?php

namespace Air\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;


class PostsController extends Controller
{

    protected $itemsLimit = 3;

    /**
     * Ustawienie routingu z nazwą, wartością domyślną i warunkiem dla paginacji
     * @Route(
     *     "/{page}",
     *     name = "blog_index",
     *     defaults = {"page" = 1},
     *     requirements = {"page" = "\d+"}
     * )
     * @Template("AirBlogBundle:Posts:postsList.html.twig")
     */
    public function indexAction($page){
        /*
         * Można zastosować dwa zapisy:
         * return $this->render('AirBlogBundle:Posts:index.html.twig');
         * lub
         * return array(); (ale w tym przypadku musimy dopisać @Template w annotation i dodać use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
         */

        //Inny sposób pobierania danych
        //$PostRepo = $this->getDoctrine()->getRepository('AirBlogBundle:Post');
        //$allPosts = $PostRepo->findBy(array(), array('publishedDate' => 'desc'));

        $pagination = $this->getPaginatedPosts(array(
            'status' => 'published',
            'orderBy' => 'p.publishedDate',
            'orderDir' => 'DESC',
        ), $page);

        return array(
            'pagination' => $pagination,
            'listTitle' => 'Najnowsze wpisy'
        );
    }

    /**
     * @Route(
     *     "/search/{page}",
     *     name = "blog_search",
     *     defaults = {"page" = 1},
     *     requirements = {"page" = "\d+"}
     * )
     * @Template("AirBlogBundle:Posts:postsList.html.twig")
     */
    public function searchAction(Request $request, $page){

        $searchParam = $request->query->get('search');

        $pagination = $this->getPaginatedPosts(array(
            'status' => 'published',
            'orderBy' => 'p.publishedDate',
            'orderDir' => 'DESC',
            'search' => $searchParam
        ), $page);

        return array(
            'pagination' => $pagination,
            'listTitle' => sprintf('Wyniki wyszukiwania frazy "%s"', $searchParam),
            'searchPhrase' => $searchParam
        );
    }

    /**
     * @Route(
     *     "/{slug}",
     *     name = "blog_post"
     *     )
     * @Template()
     */
    public function postAction($slug) {
        $PostRepo = $this->getDoctrine()->getRepository('AirBlogBundle:Post');
        $Post = $PostRepo->getPublishedPost($slug);

        if($Post === null) {
            throw $this->createNotFoundException('Post nie został odnaleziony');
        }

        return array(
            'post' => $Post
        );
    }

    /**
     * @Route(
     *     "/category/{slug}/{page}",
     *     name = "blog_category",
     *     defaults = {"page" = 1},
     *     requirements = {"page" = "\d+"}
     * )
     * @Template("AirBlogBundle:Posts:postsList.html.twig")
     */
    public function categoryAction($slug, $page) {
        $CategoryRepo = $this->getDoctrine()->getRepository('AirBlogBundle:Category');
        $Category = $CategoryRepo->findOneBySlug($slug);

        $pagination = $this->getPaginatedPosts(array(
            'status' => 'published',
            'orderBy' => 'p.publishedDate',
            'orderDir' => 'DESC',
            'categorySlug' => $slug
        ), $page);

        return array(
            'pagination' => $pagination,
            'listTitle' => sprintf('Wpisy z kategorii "%s"', $Category->getName())
        );
    }

    /**
     * @Route(
     *     "/tag/{slug}/{page}",
     *     name = "blog_tag",
     *     defaults = {"page" = 1},
     *     requirements = {"page" = "\d+"}
     * )
     * @Template("AirBlogBundle:Posts:postsList.html.twig")
     */
    public function tagAction($slug, $page) {
        $TagRepo = $this->getDoctrine()->getRepository('AirBlogBundle:Tag');
        $Tag = $TagRepo->findOneBySlug($slug);

        $pagination = $this->getPaginatedPosts(array(
            'status' => 'published',
            'orderBy' => 'p.publishedDate',
            'orderDir' => 'DESC',
            'tagSlug' => $slug
        ), $page);

        return array(
            'pagination' => $pagination,
            'listTitle' => sprintf('Wpisy oznaczone tagiem "%s"', $Tag->getName())
        );
    }

    protected function getPaginatedPosts(array $params = array(), $page) {
        $PostRepo = $this->getDoctrine()->getRepository('Air\BlogBundle\Entity\Post');
        $qb = $PostRepo->getPosts($params);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($qb, $page, $this->itemsLimit);

        return $pagination;
    }

}
