<?php

declare(strict_types=1);

namespace App\Infra\Storage\Account;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity()]
class Token
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    #[ORM\ManyToOne(targetEntity: Permission::class, inversedBy: 'tokens')]
    private Permission $permission;

    #[ORM\Column(type: 'json')]
    private array $credentials;

    #[ORM\ManyToOne(targetEntity: Account::class, inversedBy: 'tokens')]
    private Account $account;
}
