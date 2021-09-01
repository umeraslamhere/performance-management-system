<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TeamRepository::class)
 */
class Team
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="teams")
     * @ORM\JoinColumn(nullable=false)
     */
    private $managerId;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="teams")
     */
    private $memberId;

    public function __construct()
    {
        $this->memberId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getManagerId(): ?User
    {
        return $this->managerId;
    }

    public function setManagerId(?User $managerId): self
    {
        $this->managerId = $managerId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getMemberId(): Collection
    {
        return $this->memberId;
    }

    public function addMemberId(User $memberId): self
    {
        if (!$this->memberId->contains($memberId)) {
            $this->memberId[] = $memberId;
        }

        return $this;
    }

    public function removeMemberId(User $memberId): self
    {
        $this->memberId->removeElement($memberId);

        return $this;
    }
}
