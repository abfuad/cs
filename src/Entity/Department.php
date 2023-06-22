<?php

namespace App\Entity;

use App\Repository\DepartmentRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
#[ORM\Entity(repositoryClass: DepartmentRepository::class)]
class Department extends CommonEntity
{
    #[ORM\OneToMany(mappedBy: 'department', targetEntity: UserInfo::class)]
    private Collection $userInfos;

    #[ORM\OneToMany(mappedBy: 'department', targetEntity: StudentProfile::class, orphanRemoval: true)]
    private Collection $studentProfiles;

    #[ORM\OneToMany(mappedBy: 'department', targetEntity: ClearanceStatus::class, orphanRemoval: true)]
    private Collection $clearanceStatuses;

    #[ORM\OneToOne(mappedBy: 'department', cascade: ['persist', 'remove'])]
    private ?ClearanceDepartment $clearanceDepartment = null;

  
    public function __construct()
    {
        $this->userInfos = new ArrayCollection();
        $this->studentProfiles = new ArrayCollection();
        $this->clearanceStatuses = new ArrayCollection();
    }

   

    /**
     * @return Collection<int, UserInfo>
     */
    public function getUserInfos(): Collection
    {
        return $this->userInfos;
    }

    public function addUserInfo(UserInfo $userInfo): self
    {
        if (!$this->userInfos->contains($userInfo)) {
            $this->userInfos->add($userInfo);
            $userInfo->setDepartment($this);
        }

        return $this;
    }

    public function removeUserInfo(UserInfo $userInfo): self
    {
        if ($this->userInfos->removeElement($userInfo)) {
            // set the owning side to null (unless already changed)
            if ($userInfo->getDepartment() === $this) {
                $userInfo->setDepartment(null);
            }
        }

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
            $studentProfile->setDepartment($this);
        }

        return $this;
    }

    public function removeStudentProfile(StudentProfile $studentProfile): self
    {
        if ($this->studentProfiles->removeElement($studentProfile)) {
            // set the owning side to null (unless already changed)
            if ($studentProfile->getDepartment() === $this) {
                $studentProfile->setDepartment(null);
            }
        }

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
            $clearanceStatus->setDepartment($this);
        }

        return $this;
    }

    public function removeClearanceStatus(ClearanceStatus $clearanceStatus): self
    {
        if ($this->clearanceStatuses->removeElement($clearanceStatus)) {
            // set the owning side to null (unless already changed)
            if ($clearanceStatus->getDepartment() === $this) {
                $clearanceStatus->setDepartment(null);
            }
        }

        return $this;
    }

    public function getClearanceDepartment(): ?ClearanceDepartment
    {
        return $this->clearanceDepartment;
    }

    public function setClearanceDepartment(ClearanceDepartment $clearanceDepartment): self
    {
        // set the owning side of the relation if necessary
        if ($clearanceDepartment->getDepartment() !== $this) {
            $clearanceDepartment->setDepartment($this);
        }

        $this->clearanceDepartment = $clearanceDepartment;

        return $this;
    }

   
  
}
