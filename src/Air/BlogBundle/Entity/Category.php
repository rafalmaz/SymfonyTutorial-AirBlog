<?php

namespace Air\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Category
 * @package Air\BlogBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="blog_categories")
 */
class Category extends AbstractTaxonomy {

    /**
     * @ORM\OneToMany(targetEntity="Post", mappedBy="category")
     */
    protected $posts;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add posts
     *
     * @param \Air\BlogBundle\Entity\Post $posts
     * @return Category
     */
    public function addPost(\Air\BlogBundle\Entity\Post $posts)
    {
        $this->posts[] = $posts;

        return $this;
    }

    /**
     * Remove posts
     *
     * @param \Air\BlogBundle\Entity\Post $posts
     */
    public function removePost(\Air\BlogBundle\Entity\Post $posts)
    {
        $this->posts->removeElement($posts);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPosts()
    {
        return $this->posts;
    }
}
