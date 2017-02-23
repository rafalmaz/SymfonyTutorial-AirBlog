<?php

namespace Air\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class AbstractTaxonomy
 * @package Air\BlogBundle\Entity
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class AbstractTaxonomy {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=120, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=120, unique=true)
     */
    private $slug;

    protected $posts;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return AbstractTaxonomy
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return AbstractTaxonomy
     */
    public function setSlug($slug)
    {
        $this->slug = \Air\BlogBundle\Libs\Utils::sluggify($slug);

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function preSave() {
        if($this->slug == null) {
            $this->setSlug($this->getName());
        }
    }
}
