<?php

declare(strict_types=1);

namespace App\Infra\Order;

use App\Domain\Asset\AssetInterface;
use App\Domain\Order\OrderStatus;
use App\Domain\Order\OrderType;
use App\Infra\Asset\Asset;
use App\Infra\Storage\Account\Transaction;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity()]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    #[ORM\ManyToOne(targetEntity: Asset::class)]
    private AssetInterface $asset;

    #[ORM\Column(type: 'float')]
    private float $quantity;

    #[ORM\Column(type: 'string')]
    private string $direction;

    #[ORM\Column(type: 'string', enumType: OrderType::class)]
    private OrderType $orderType;

    #[ORM\Column(type: 'integer')]
    private int $inputTime;

    #[ORM\Column(type: 'integer')]
    private int $transmitionTime = 0;

    #[ORM\Column(type: 'string', enumType: OrderStatus::class)]
    private OrderStatus $status;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: Transaction::class)]
    #[ORM\JoinColumn(name: 'transaction_id', referencedColumnName: 'id')]
    private Collection $transactions;

    public function __construct(
        Uuid $id,
        AssetInterface $asset,
        float $quantity,
        string $direction,
        OrderType $orderType
    ) {
        $this->id = $id;
        $this->asset = $asset;
        $this->quantity = $quantity;
        $this->direction = $direction;
        $this->orderType = $orderType;
        $this->inputTime = time() * 1000;
        $this->transactions = new ArrayCollection();
    }

    public function addTransaction(Transaction $transaction): void
    {
        if(!$this->transactions->contains($transaction)){
            $this->transactions->add($transaction);
        }
    }
    public function removeTransaction(Transaction $transaction): void
    {
        if($this->transactions->contains($transaction)){
            $this->transactions->removeElement($transaction);
        }
    }


    public function getStatus(): OrderStatus
    {
        return $this->status;
    }

    public function setTransmitionTime(int $transmitionTime): void
    {
        $this->transmitionTime = $transmitionTime;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getAsset(): AssetInterface
    {
        return $this->asset;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }

    public function getOrderType(): OrderType
    {
        return $this->orderType;
    }

    public function getInputTime(): int
    {
        return $this->inputTime;
    }

    public function getTransmitionTime(): int
    {
        return $this->transmitionTime;
    }
}