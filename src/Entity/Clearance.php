<?php

namespace App\Entity;

use App\Repository\ClearanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClearanceRepository::class)]
class Clearance extends BaseEntity
{
    
    #[ORM\ManyToOne(inversedBy: 'clearances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserInfo $student = null;

    

    #[ORM\Column]
    private ?bool $completed = null;

    #[ORM\OneToMany(mappedBy: 'clearance', targetEntity: ClearanceStatus::class, orphanRemoval: true)]
    private Collection $clearanceStatuses;

    #[ORM\ManyToOne(inversedBy: 'clearances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Reason $reason = null;

    public function __construct()
    {
        // $this->reason = new ArrayCollection();
        $this->clearanceStatuses = new ArrayCollection();
    }

    
    public function getStudent(): ?UserInfo
    {
        return $this->student;
    }

    public function setStudent(?UserInfo $student): self
    {
        $this->student = $student;

        return $this;
    }

    

    public function isCompleted(): ?bool
    {
        return $this->completed;
    }

    public function setCompleted(bool $completed): self
    {
        $this->completed = $completed;

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
            $clearanceStatus->setClearance($this);
        }

        return $this;
    }

    public function removeClearanceStatus(ClearanceStatus $clearanceStatus): self
    {
        if ($this->clearanceStatuses->removeElement($clearanceStatus)) {
            // set the owning side to null (unless already changed)
            if ($clearanceStatus->getClearance() === $this) {
                $clearanceStatus->setClearance(null);
            }
        }

        return $this;
    }

    public function getReason(): ?Reason
    {
        return $this->reason;
    }

    public function setReason(?Reason $reason): self
    {
        $this->reason = $reason;

        return $this;
    }
}
