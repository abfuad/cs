<?php

namespace App\Entity;

use App\Repository\ClearanceStatusRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClearanceStatusRepository::class)]
class ClearanceStatus extends BaseEntity
{
    

    #[ORM\ManyToOne(inversedBy: 'clearanceStatuses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Clearance $clearance = null;

    #[ORM\ManyToOne(inversedBy: 'clearanceStatuses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Department $department = null;

    #[ORM\ManyToOne(inversedBy: 'clearanceStatuses')]
    private ?User $approvedBy = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $approvedAt = null;

    #[ORM\Column]
    private ?int $status = null;


    public function getClearance(): ?Clearance
    {
        return $this->clearance;
    }

    public function setClearance(?Clearance $clearance): self
    {
        $this->clearance = $clearance;

        return $this;
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

    public function getApprovedBy(): ?User
    {
        return $this->approvedBy;
    }

    public function setApprovedBy(?User $approvedBy): self
    {
        $this->approvedBy = $approvedBy;

        return $this;
    }

    public function getApprovedAt(): ?\DateTimeInterface
    {
        return $this->approvedAt;
    }

    public function setApprovedAt(?\DateTimeInterface $approvedAt): self
    {
        $this->approvedAt = $approvedAt;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }
}
