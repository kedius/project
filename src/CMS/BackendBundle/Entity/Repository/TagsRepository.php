<?php

namespace CMS\BackendBundle\Entity\Repository;


use CMS\BackendBundle\Helper\KeeperDataTable;
use Doctrine\ORM\EntityRepository;

class TagsRepository extends EntityRepository
{
    public function getAllTagsWithCount()
    {
        $qb = $this->createQueryBuilder('t');
        $qb ->leftJoin('t.contents', 'content')->addSelect('partial content.{id}');
        $query = $qb->getQuery();
        return $query->getResult();
    }
}