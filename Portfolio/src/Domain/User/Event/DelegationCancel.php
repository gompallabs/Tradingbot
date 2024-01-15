<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use Symfony\Component\Uid\Uuid;

class DelegationCancel
{
    private Uuid $userId;
    private Uuid $managerId;
    private Uuid $accountId;

    public function __construct(Uuid $userId, Uuid $managerId, Uuid $accountId)
    {
        $this->userId = $userId;
        $this->managerId = $managerId;
        $this->accountId = $accountId;
    }

    public function getUserId(): Uuid
    {
        return $this->userId;
    }

    public function getManagerId(): Uuid
    {
        return $this->managerId;
    }

    public function getAccountId(): Uuid
    {
        return $this->accountId;
    }
}