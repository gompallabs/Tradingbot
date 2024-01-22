<?php

namespace App\Infra\User;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/*
 * A Squad has one or many users
 */
#[ORM\Entity()]
class Squad
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'squads')]
    #[ORM\JoinTable(name: 'users_squads')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: true)]
    #[ORM\InverseJoinColumn(name: 'squad_id', referencedColumnName: 'id', nullable: true)]
    private Collection $users;

    #[ORM\Column(type: 'string')]
    private string $name;

    public function __construct(Uuid $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->users = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
