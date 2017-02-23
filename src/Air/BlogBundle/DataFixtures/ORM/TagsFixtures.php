<?php

namespace Air\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Air\BlogBundle\Entity\Tag;


class TagsFictures extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {

        $tagsList = array(
            'dolor',
            'ullamcorper',
            'suspendisse',
            'pellentesque',
            'maecenas',
            'malesuada',
            'ultricies',
            'etiam',
            'quisque',
            'fringilla',
            'eleifend',
            'bibendum',
            'faucibus',
            'luctus',
            'vestibulum'
        );

        foreach ($tagsList as $value) {
            $Tag = new Tag();
            $Tag->setName($value);

            $manager->persist($Tag);
            $this->addReference('tag_'.$value, $Tag);
        }

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder() {
        return 0;
    }

}