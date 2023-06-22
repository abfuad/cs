<?php

namespace App\Entity;

use App\Repository\ReasonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReasonRepository::class)]
class Reason extends CommonEntity
{
   
  
    #[Assert\GreaterThanOrEqual('today')]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $startAt = null;
    
    #[Assert\GreaterThan(propertyPath:"startAt")]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $endAt = null;

    #[ORM\Column]
    private ?bool $active = true;

    #[ORM\OneToMany(mappedBy: 'reason', targetEntity: Clearance::class)]
    private Collection $clearances;

    public function __construct()
    {
        $this->clearances = new ArrayCollection();
    }

   

   

    

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeInterface $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTimeInterface $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

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
            $clearance->setReason($this);
        }

        return $this;
    }

    public function removeClearance(Clearance $clearance): self
    {
        if ($this->clearances->removeElement($clearance)) {
            // set the owning side to null (unless already changed)
            if ($clearance->getReason() === $this) {
                $clearance->setReason(null);
            }
        }

        return $this;
    }
}
