<?php

declare(strict_types=1);

namespace App\Infra\Storage\Account;

use App\Domain\Storage\Account as AccountInterface;
use App\Domain\Storage\CustodialStorage;
use App\Domain\Storage\Orm\Doctrine\AccountType;
use App\Infra\Storage\Storage;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/*
 * An account is an envelope to hold assets and to reflect transactions, e.g. custodial
 * or it can be virtual in order to perform calculations
 * One account can have multiple sub-accounts.
 * For ex Bybit account have funding, and unified trading,
 * Bitget have funding, spot trading, margin trading, derivatives trading
 * A traditional broker may have a funding account, a margin account (sometimes called derivative account)
 * To simplify, we will keep the scheme: funding, savings (earning), spot, margin, futures accounts
 * An account have one Owner or a group of Owner, and can have one or more manager
 * One storage can have multiple accounts (with multiple sub-accounts)
 */
#[ORM\Entity()]
class Account implements AccountInterface, CustodialStorage
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: Account::class)]
    private Collection $subAccounts;

    #[ORM\ManyToOne(targetEntity: Account::class, inversedBy: 'subAccounts')]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id')]
    private Account|null $parent = null;

    #[ORM\Column(type: 'boolean')]
    private bool $virtual;

    #[ORM\ManyToOne(targetEntity: Storage::class, inversedBy: 'account')]
    #[ORM\JoinColumn(name: 'storage_id', referencedColumnName: 'id')]
    private Storage $storage;

    #[ORM\OneToMany(mappedBy: 'account', targetEntity: Token::class)]
    private Collection $tokens;

    #[ORM\OneToMany(mappedBy: 'account', targetEntity: Transaction::class)]
    private Collection $transactions;

    #[ORM\OneToMany(mappedBy: 'account', targetEntity: Permission::class, cascade: ['all'])]
    #[ORM\JoinColumn(name: 'permission_id', referencedColumnName: 'id')]
    private Collection $permissions;

    #[ORM\Column(type: 'string', enumType: AccountType::class)]
    private AccountType $type;


    public function __construct(Uuid $id, AccountType $type)
    {
        $this->id = $id;
        $this->subAccounts = new ArrayCollection();
        $this->tokens = new ArrayCollection();
        $this->permissions = new ArrayCollection();
        $this->virtual = false;
        $this->type = $type;
    }

    public function getType(): AccountType
    {
        return $this->type;
    }

    public function getCustodian(): string
    {
        // TODO: Implement getCustodian() method.
    }

    public function getTokens(): Collection
    {
        return $this->tokens;
    }

    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    public function addPermission(Permission $permission): void
    {
        if (!$this->permissions->contains($permission)) {
            $this->permissions->add($permission);
        }
    }

    public function removePermission(Permission $permission): void
    {
        if ($this->permissions->contains($permission)) {
            $this->permissions->removeElement($permission);
        }
    }

    public function getSubAccounts(): Collection
    {
        return $this->subAccounts;
    }

    public function getStorage(): Storage
    {
        return $this->storage;
    }

    public function getStorageName(): string
    {
        return $this->storage->getName();
    }

    public function addSubAccount(Account $account): void
    {
        if (!$this->subAccounts->contains($account)) {
            $this->subAccounts->add($account);
        }
    }

    public function removeSubAccount(Account $account): void
    {
        if ($this->subAccounts->contains($account)) {
            $this->subAccounts->removeElement($account);
        }
    }

    public function deposit()
    {
        // TODO: Implement deposit() method.
    }

    public function withdraw()
    {
        // TODO: Implement withdraw() method.
    }

    public function transfer()
    {
        // TODO: Implement transfer() method.
    }

    public function getBalance()
    {
        // TODO: Implement getBalance() method.
    }

    public function getTransactions()
    {
        // TODO: Implement getTransactions() method.
    }

    public function hasRestApi(): bool
    {
        // TODO: Implement hasRestApi() method.
    }

    public function hasWsApi(): bool
    {
        // TODO: Implement hasWsApi() method.
    }

    public function getParent(): ?Account
    {
        return $this->parent;
    }

    public function setParent(?Account $parent): void
    {
        $this->parent = $parent;
    }

    public function isVirtual(): bool
    {
        return $this->virtual;
    }

    public function setVirtual(bool $virtual): void
    {
        $this->virtual = $virtual;
    }

    public function geStorage(): \App\Domain\Storage\Storage
    {
        return $this->storage;
    }

    public function setStorage(Storage $storage): void
    {
        $this->storage = $storage;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
