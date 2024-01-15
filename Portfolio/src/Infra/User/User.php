<?php

namespace App\Infra\User;

use App\Domain\User\OwnerInterface;
use App\Infra\Storage\Account\Permission;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity()]
#[ORM\Table(name: 'person')]
class User implements OwnerInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    #[ORM\ManyToMany(targetEntity: Squad::class, inversedBy: 'users')]
    private Collection $squads;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Permission::class)]
    private Collection $permissions;

    #[ORM\Column(type: 'string', unique: true)]
    private string $username;

    public function __construct(Uuid $id, string $username)
    {
        $this->squads = new ArrayCollection();
        $this->permissions = new ArrayCollection();
        $this->id = $id;
        $this->username = $username;
    }

    public function addSquad(Squad $squad): void
    {
        if (!$this->squads->contains($squad)) {
            $this->squads->add($squad);
        }
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getSquads(): Collection
    {
        return $this->squads;
    }

    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}
