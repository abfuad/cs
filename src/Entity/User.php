<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User extends BaseEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
   
    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private ?bool $isActive = true;

    #[ORM\Column]
    private ?bool $isStudent = false;


    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?UserInfo $userInfo = null;

    #[ORM\OneToMany(mappedBy: 'approvedBy', targetEntity: ClearanceStatus::class)]
    private Collection $clearanceStatuses;

    public function __construct()
    {
        $this->clearanceStatuses = new ArrayCollection();
    }

  

    public function __toString()
    {
        return $this->userInfo->getFullName();
    
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
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
     * @see PasswordAuthenticatedUserInterface
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
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function isIsStudent(): ?bool
    {
        return $this->isStudent;
    }

    public function setIsStudent(bool $isStudent): self
    {
        $this->isStudent = $isStudent;

        return $this;
    }

  

   

    

    public function getUserInfo(): ?UserInfo
    {
        return $this->userInfo;
    }

    public function setUserInfo(UserInfo $userInfo): self
    {
        // set the owning side of the relation if necessary
        if ($userInfo->getUser() !== $this) {
            $userInfo->setUser($this);
        }

        $this->userInfo = $userInfo;

        return $this;
    }

    /**
     * @return Collection<int, ClearanceStatus>
     */
    public function getClearanceStatuses(): Collection
    {
        return $this->clearanceStatuses;
    }

    public function addClearanceStatus(ClearanceStatus $clearanceStatus): self
    {
        if (!$this->clearanceStatuses->contains($clearanceStatus)) {
            $this->clearanceStatuses->add($clearanceStatus);
            $clearanceStatus->setApprovedBy($this);
        }

        return $this;
    }

    public function removeClearanceStatus(ClearanceStatus $clearanceStatus): self
    {
        if ($this->clearanceStatuses->removeElement($clearanceStatus)) {
            // set the owning side to null (unless already changed)
            if ($clearanceStatus->getApprovedBy() === $this) {
                $clearanceStatus->setApprovedBy(null);
            }
        }

        return $this;
    }
}
