<?php

declare(strict_types=1);

namespace App\Infra\Storage\Account;

use App\Infra\Asset\Asset;
use App\Infra\Order\Order;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity()]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    #[ORM\ManyToOne(targetEntity: Account::class, inversedBy: 'transactions')]
    #[ORM\JoinColumn(name: 'account_id', referencedColumnName: 'id')]
    private Account $account;

    #[ORM\Column(type: 'float')]
    private float $quantity;

    #[ORM\Column(type: 'float')]
    private float $price;

    #[ORM\ManyToOne(targetEntity: Asset::class, inversedBy: 'transactions')]
    #[ORM\JoinColumn(name: 'asset_id', referencedColumnName: 'id')]
    private Asset $asset;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'transactions')]
    private Order $order;

    public function __construct(Uuid $id, Account $account)
    {
        $this->id = $id;
        $this->account = $account;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function setOrder(Order $order): void
    {
        $this->order = $order;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): void
    {
        $this->account = $account;
    }
}
