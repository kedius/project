<?php

namespace CMS\BackendBundle\Entity\Repository;

use CMS\BackendBundle\Helper\KeeperDataTable;
use Doctrine\ORM\EntityRepository;

class ContentRepository extends EntityRepository
{

    public function findAllContent($status = null)
    {
        $qb = $this->getContentQueryBuilder('c');
        $qb ->orderBy('c.dateAt', 'DESC');

        if ($status) {
            $qb ->where('status.id = :status')
                ->setParameter(':status', $status);
        }

        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function findAllContentByUser($username, $status = null)
    {
        $qb = $this->getContentQueryBuilder('c');
        $qb ->where('author.username = :username')
            ->orderBy('c.dateAt', 'DESC')
            ->setParameter(':username', $username);

        if ($status) {
            $qb ->andWhere('status.id = :status')
                ->setParameter(':status', $status);
        }

        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function findAllContentByTag($tag, $status = null)
    {
        $qb = $this->getContentQueryBuilder('c');
        $qb ->where('tags.id = :tag')
            ->orderBy('c.dateAt', 'DESC')
            ->setParameter(':tag', $tag);

        if ($status) {
            $qb ->andWhere('status.id = :status')
                ->setParameter(':status', $status);
        }

        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function findAllContentByCategory($category, $status = null)
    {
        $qb = $this->getContentQueryBuilder('c');
        $qb ->where('category.alias = :category')
            ->orderBy('c.dateAt', 'DESC')
            ->setParameter(':category', $category);

        if ($status) {
            $qb ->andWhere('status.id = :status')
                ->setParameter(':status', $status);
        }

        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function getContentByAlias($alias, $status = null)
    {
        $qb = $this->getContentQueryBuilder('c');
        $qb ->where('c.alias = :alias')
            ->orderBy('c.dateAt', 'DESC')
            ->setParameter(':alias', $alias);

        if ($status) {
            $qb ->andWhere('status.id = :status')
                ->setParameter(':status', $status);
        }

        $query = $qb->getQuery();
        return $query->getOneOrNullResult();
    }

    public function getMyReviewContent($userID)
    {
        $qb = $this->getContentQueryBuilder('c');
        $qb ->where('publisher.id = :userID')
            ->orderBy('c.publicDate', 'DESC')
            ->setParameter(':userID', $userID);

        $query = $qb->getQuery();

        return $query->getResult();
    }

    public function getMyContents($userID, $status)
    {
        $qb = $this->getContentQueryBuilder('c');
        $qb ->orderBy('c.dateAt', 'DESC')
            ->where('author.id = :userID')
            ->setParameter(':userID', $userID);

        if ($status) {
            $qb ->andWhere('status.id = :statusID')
                ->setParameter(':statusID', $status);
        }

        $query = $qb->getQuery();

        return $query->getResult();
    }

    private function getContentQueryBuilder($alias)
    {
        $qb = $this->createQueryBuilder($alias);
        $qb ->join($alias.'.status', 'status')->addSelect('status')
            ->join($alias.'.contentType', 'type')->addSelect('type')
            ->join($alias.'.author', 'author')->addSelect('author')
            ->join($alias.'.category', 'category')->addSelect('category')
            ->leftJoin($alias.'.files','files')->addSelect('files')
            ->leftJoin($alias.'.review','review')->addSelect('review')
            ->leftJoin($alias.'.tags','tags')->addSelect('tags')
            ->leftJoin($alias.'.publisher', 'publisher')->addSelect('publisher')
            ->leftJoin($alias.'.comments', 'comments')->addSelect('comments');
        return $qb;
    }

}