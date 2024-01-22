<?php

namespace App\Domain\User\Orm\Doctrine;

use App\Domain\User\OwnerInterface;

interface SquadRepositoryInterface
{
    public function getSquadsOfUser(OwnerInterface $user);

    public function find($id, $lockMode = null, $lockVersion = null);

    public function findOneBy(array $criteria, array $orderBy = null);

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);

    public function findAll();
}
