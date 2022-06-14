<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=EmployeeRepository::class)
 */
class Employee
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="doctrine.uuid_generator")
     * @Groups("employee")
     * @Groups("employee_list")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("employee")
     * @Groups("employee_list")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Shift::class, mappedBy="employee", orphanRemoval=true)
     * @Groups("employee")
     */
    private $shifts;

    /**
     * @ORM\ManyToOne(targetEntity=Shift::class)
     * @Groups("employee")
     */
    private $currentShift;

    /**
     * @ORM\Column(type="boolean")
     * @Groups("employee")
     */
    private $active;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups("employee")
     */
    private $maxHoursPerMonth;

    public function __construct()
    {
        $this->shifts = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
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
     * @return Collection<int, Shift>
     */
    public function getShifts(): Collection
    {
        return $this->shifts;
    }

    public function addShift(Shift $shift): self
    {
        if (!$this->shifts->contains($shift)) {
            $this->shifts[] = $shift;
            $shift->setEmployee($this);
        }

        return $this;
    }

    public function removeShift(Shift $shift): self
    {
        if ($this->shifts->removeElement($shift)) {
            // set the owning side to null (unless already changed)
            if ($shift->getEmployee() === $this) {
                $shift->setEmployee(null);
            }
        }

        return $this;
    }

    public function getCurrentShift(): ?Shift
    {
        return $this->currentShift;
    }

    public function setCurrentShift(?Shift $currentShift): self
    {
        $this->currentShift = $currentShift;

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

    public function getMaxHoursPerMonth(): ?int
    {
        return $this->maxHoursPerMonth;
    }

    public function setMaxHoursPerMonth(?int $maxHoursPerMonth): self
    {
        $this->maxHoursPerMonth = $maxHoursPerMonth;

        return $this;
    }
}
