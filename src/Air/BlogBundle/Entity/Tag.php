<?php

namespace Air\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Category
 * @package Air\BlogBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="blog_tags")
 */
class Tag extends AbstractTaxonomy {

    /**
     * @ORM\ManyToMany(targetEntity="Post", mappedBy="tags")
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
     * @return Tag
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
