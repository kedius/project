<?php

namespace CMS\BackendBundle\Entity\Repository;


use CMS\BackendBundle\Helper\KeeperDataTable;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function getUserByUsername($username)
    {
        $qb = $this->createQueryBuilder('u');
        $qb ->leftJoin('u.roles', 'roles')->addSelect('roles')
            ->where('u.username = :username')
            ->setParameter(':username', $username);
        $query = $qb->getQuery();
        return $query->getOneOrNullResult();
    }

    public function findAllUsers()
    {
        $qb = $this->createQueryBuilder('u');
        $qb ->leftJoin('u.roles', 'roles')->addSelect('roles');
        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function searchUser($username)
    {
        $qb = $this->createQueryBuilder('u');
        $qb ->leftJoin('u.roles', 'roles')->addSelect('roles')
            ->where('u.username LIKE :username')
            ->setParameter(':username', '%'.$username.'%');
        $query = $qb->getQuery();
        return $query->getResult();
    }
}