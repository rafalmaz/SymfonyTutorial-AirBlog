<?php

namespace Air\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;


class PostRepository extends EntityRepository
{

    public function getPublishedPost($slug) {
        $query = $this->getPosts(array(
                'status' => 'published'
            )
        );
        $query->andWhere('p.slug = :slug')
            ->setParameter('slug', $slug);

        return $query->getQuery()->getOneOrNullResult();
    }

    public function getPosts(array $params = array()) {
        $query = $this->createQueryBuilder('p')
                        ->select('p')
                        ->leftJoin('p.category', 'c')
                        ->leftJoin('p.tags', 't');

        if ($params['status'] === 'published') {
            $query->where('p.publishedDate <= :currDate AND p.publishedDate IS NOT NULL')
                    ->setParameter('currDate', new \DateTime());
        }
        else if ($params['status'] === 'unpublished') {
            $query->where('p.publishedDate > :currDate OR p.publishedDate IS NULL')
                ->setParameter('currDate', new \DateTime());
        }

        if(!empty($params['orderBy'])) {
            $orderDir = (!empty($params['orderBir'])) ? $params['orderBir'] : NULL;
            $query->orderBy($params['orderBy'], $orderDir);
        }

        if(!empty($params['categorySlug'])) {
            $query->andWhere('c.slug = :slug')
                ->setParameter('slug', $params['categorySlug']);
        }

        if(!empty($params['tagSlug'])) {
            $query->andWhere('t.slug = :slug')
                ->setParameter('slug', $params['tagSlug']);
        }

        if(!empty($params['search'])) {
            $searchParams = '%'.$params['search'].'%';
            $query->andWhere('p.title LIKE :searchParams OR p.content LIKE :searchParams ')
                ->setParameter('searchParams', $searchParams);
        }

        return $query;
    }

    public function getPublishedPostExample($slug) {
        $query = $this->createQueryBuilder('p')
                        ->select('p')
                        ->where('p.slug = :slug')
                        ->andWhere('p.publishedDate IS NOT NULL')
                        ->setParameter('slug', $slug);

        return $query->getQuery()->getOneOrNullResult();
    }

}