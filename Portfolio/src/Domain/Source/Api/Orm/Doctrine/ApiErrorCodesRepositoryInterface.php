<?php

namespace App\Domain\Source\Api\Orm\Doctrine;

use App\Infra\Source\ApiErrorCodes;

interface ApiErrorCodesRepositoryInterface
{
    public function save(ApiErrorCodes $code): void;

    public function find($id, $lockMode = null, $lockVersion = null);

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);

    public function findAll();

    public function findOneBy(array $criteria, array $orderBy = null);
}
