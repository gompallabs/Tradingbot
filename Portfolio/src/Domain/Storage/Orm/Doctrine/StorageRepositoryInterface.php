<?php

namespace App\Domain\Storage\Orm\Doctrine;

use App\Domain\Storage\Storage;
use App\Infra\Storage\Account\Custodian;

interface StorageRepositoryInterface extends Storage
{
    public function find($id, $lockMode = null, $lockVersion = null);

    public function findAll();

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);

    public function findOneBy(array $criteria, array $orderBy = null);

    public function save(Custodian $custodian): void;
}
