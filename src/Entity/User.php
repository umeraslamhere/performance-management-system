<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\OneToMany(targetEntity=ApiToken::class, mappedBy="user", orphanRemoval=true)
     */
    private $apiTokens;

    /**
     * @ORM\Column(type="string", length=11, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=12, nullable=true)
     */
    private $gender;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $picture;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="managerId")
     */
    private $commentsBy;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="subordinateId")
     */
    private $commentsOn;

    /**
     * @ORM\OneToMany(targetEntity=Team::class, mappedBy="managerId")
     */
    private $teams;

    /**
     * @ORM\ManyToMany(targetEntity=Team::class, mappedBy="memberId")
     * 
     */
    private $myteams;



    public function __construct()
    {
        $this->apiTokens = new ArrayCollection();
        $this->commentsBy = new ArrayCollection();
        $this->commentsOn = new ArrayCollection();
        $this->teams = new ArrayCollection();
        $this->myteams = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return Collection|ApiToken[]
     */
    public function getApiTokens(): Collection
    {
        return $this->apiTokens;
    }

    public function addApiToken(ApiToken $apiToken): self
    {
        if (!$this->apiTokens->contains($apiToken)) {
            $this->apiTokens[] = $apiToken;
            $apiToken->setUser($this);
        }

        return $this;
    }

    public function removeApiToken(ApiToken $apiToken): self
    {
        if ($this->apiTokens->removeElement($apiToken)) {
            // set the owning side to null (unless already changed)
            if ($apiToken->getUser() === $this) {
                $apiToken->setUser(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(?int $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getCommentsBy(): Collection
    {
        return $this->commentsBy;
    }

    public function addCommentsBy(Comment $commentsBy): self
    {
        if (!$this->commentsBy->contains($commentsBy)) {
            $this->commentsBy[] = $commentsBy;
            $commentsBy->setManagerId($this);
        }

        return $this;
    }

    public function removeCommentsBy(Comment $commentsBy): self
    {
        if ($this->commentsBy->removeElement($commentsBy)) {
            // set the owning side to null (unless already changed)
            if ($commentsBy->getManagerId() === $this) {
                $commentsBy->setManagerId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getCommentsOn(): Collection
    {
        return $this->commentsOn;
    }

    public function addCommentsOn(Comment $commentsOn): self
    {
        if (!$this->commentsOn->contains($commentsOn)) {
            $this->commentsOn[] = $commentsOn;
            $commentsOn->setSubordinateId($this);
        }

        return $this;
    }

    public function removeCommentsOn(Comment $commentsOn): self
    {
        if ($this->commentsOn->removeElement($commentsOn)) {
            // set the owning side to null (unless already changed)
            if ($commentsOn->getSubordinateId() === $this) {
                $commentsOn->setSubordinateId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Team[]
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->teams->contains($team)) {
            $this->teams[] = $team;
            $team->setManagerId($this);
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        if ($this->teams->removeElement($team)) {
            // set the owning side to null (unless already changed)
            if ($team->getManagerId() === $this) {
                $team->setManagerId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Team[]
     */
    public function getMyteams(): Collection
    {
        return $this->myteams;
    }

    public function addMyteam(Team $myteam): self
    {
        if (!$this->myteams->contains($myteam)) {
            $this->myteams[] = $myteam;
            $myteam->addMemberId($this);
        }

        return $this;
    }

    public function removeMyteam(Team $myteam): self
    {
        if ($this->myteams->removeElement($myteam)) {
            $myteam->removeMemberId($this);
        }

        return $this;
    }

    public function __toString(){
        return $this->firstName." ".$this->lastName;
    }
}
