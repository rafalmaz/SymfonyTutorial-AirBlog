<?php

namespace Air\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;


class TagRepository extends EntityRepository {

    public function getTagsListOcc() {
        $query = $this->createQueryBuilder('t')
            ->select('t.slug, t.name, COUNT(p) as occ')
            ->leftJoin('t.posts', 'p')
            ->groupBy('t.name, t.slug');

        //Zwracanie tablicy tablic asocjacyjnych
        return $query->getQuery()->getArrayResult();
    }

}