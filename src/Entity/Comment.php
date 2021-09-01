<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commentsBy")
     */
    private $managerId;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commentsOn")
     */
    private $subordinateId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $content;

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

    public function getSubordinateId(): ?User
    {
        return $this->subordinateId;
    }

    public function setSubordinateId(?User $subordinateId): self
    {
        $this->subordinateId = $subordinateId;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
