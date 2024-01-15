<?php

declare(strict_types=1);

namespace App\Infra\Storage\Account;

use App\Domain\User\OwnerInterface;
use App\Infra\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity()]
class Permission
{
    /*
     * o = owner
     * r = read
     * t = trade
     * w = withdraw
     */
    public const VALUES = ['o', 'r', 't', 'w'];

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'permissions')]
    private OwnerInterface $user;

    #[ORM\Column(type: 'json')]
    private array $permissions;

    #[ORM\OneToMany(mappedBy: 'permission', targetEntity: Token::class)]
    private Collection $tokens;

    #[ORM\ManyToOne(targetEntity: Account::class, inversedBy: 'permissions')]
    private Account $account;

    public function __construct(Uuid $id, OwnerInterface $user, Account $account)
    {
        $this->id = $id;
        $this->user = $user;
        $this->account = $account;
        $this->tokens = new ArrayCollection();
        $this->permissions = [];
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): void
    {
        $this->account = $account;
    }

    public function addPermission(string $value): void
    {
        if (
            in_array(trim(strtolower($value)), self::VALUES)
            && !in_array($value, $this->permissions)
        ) {
            $this->permissions[] = $value;
        }
    }

    public function removePermission()
    {
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getUser(): OwnerInterface
    {
        return $this->user;
    }

    public function getPermissions(): array
    {
        return $this->permissions;
    }

    public function getTokens(): Collection
    {
        return $this->tokens;
    }
}
