<?php

namespace App\Entity;

use App\Repository\StudentProfileRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentProfileRepository::class)]
class StudentProfile extends CommonEntity
{
    
    #[ORM\ManyToOne(inversedBy: 'studentProfiles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserInfo $student = null;

    #[ORM\ManyToOne(inversedBy: 'studentProfiles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Department $department = null;

    #[ORM\Column]
    private ?bool $solved = null;

   

    public function getStudent(): ?UserInfo
    {
        return $this->student;
    }

    public function setStudent(?UserInfo $student): self
    {
        $this->student = $student;

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

    public function isSolved(): ?bool
    {
        return $this->solved;
    }

    public function setSolved(bool $solved): self
    {
        $this->solved = $solved;

        return $this;
    }
}
