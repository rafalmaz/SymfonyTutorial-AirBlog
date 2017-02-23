<?php

namespace Air\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Air\BlogBundle\Entity\Category;


class CategoriesFixtures extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {

        $categoriesList = array(
            'osobowe'   => 'Samoloty osobowe i pasażerskie',
            'odrzutowe'   => 'Samoloty odrzutowe',
            'wojskowe'   => 'Samoloty wojskowe',
            'kosmiczne'   => 'Promy kosmiczne',
            'tajne'   => 'Tajne rozwiązania',
        );

        foreach ($categoriesList as $key => $value) {
            $Category = new Category();
            $Category->setName($key);

            $manager->persist($Category);
            $this->addReference('category_'.$key, $Category);
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