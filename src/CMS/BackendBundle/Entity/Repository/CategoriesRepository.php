<?php

namespace CMS\BackendBundle\Entity\Repository;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class CategoriesRepository extends NestedTreeRepository
{
    public function searchCategory($category)
    {
        $qb = $this->createQueryBuilder('c');
        $qb ->where('c.title LIKE :category')
            ->setParameter(':category', '%'.$category.'%');
        $query = $qb->getQuery();
        return $query->getResult();
    }
}