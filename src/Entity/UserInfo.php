<?php

namespace App\Entity;

use App\Repository\UserInfoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserInfoRepository::class)]
class UserInfo extends UserEntity
{
    
    #[ORM\ManyToOne(inversedBy: 'userInfos')]
    #[ORM\JoinColumn(nullable: true)]

    private ?Department $department = null;

    #[ORM\OneToOne(inversedBy: 'userInfo', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'student', targetEntity: StudentProfile::class, orphanRemoval: true)]
    private Collection $studentProfiles;

    #[ORM\OneToMany(mappedBy: 'student', targetEntity: Clearance::class, orphanRemoval: true)]
    private Collection $clearances;

    public function __construct()
    {
        $this->studentProfiles = new ArrayCollection();
        $this->clearances = new ArrayCollection();
    }

   


    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, StudentProfile>
     */
    public function getStudentProfiles(): Collection
    {
        return $this->studentProfiles;
    }

    public function addStudentProfile(StudentProfile $studentProfile): self
    {
        if (!$this->studentProfiles->contains($studentProfile)) {
            $this->studentProfiles->add($studentProfile);
            $studentProfile->setStudent($this);
        }

        return $this;
    }

    public function removeStudentProfile(StudentProfile $studentProfile): self
    {
        if ($this->studentProfiles->removeElement($studentProfile)) {
            // set the owning side to null (unless already changed)
            if ($studentProfile->getStudent() === $this) {
                $studentProfile->setStudent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Clearance>
     */
    public function getClearances(): Collection
    {
        return $this->clearances;
    }

    public function addClearance(Clearance $clearance): self
    {
        if (!$this->clearances->contains($clearance)) {
            $this->clearances->add($clearance);
            $clearance->setStudent($this);
        }

        return $this;
    }

    public function removeClearance(Clearance $clearance): self
    {
        if ($this->clearances->removeElement($clearance)) {
            // set the owning side to null (unless already changed)
            if ($clearance->getStudent() === $this) {
                $clearance->setStudent(null);
            }
        }

        return $this;
    }

    
}
