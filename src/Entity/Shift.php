<?php

namespace App\Entity;

use App\Repository\ShiftRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ShiftRepository::class)
 */
class Shift
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("employee")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("employee")
     */
    private $startTime;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("employee")
     */
    private $endTime;

    /**
     * @ORM\ManyToOne(targetEntity=Employee::class, inversedBy="shifts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $employee;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups("employee")
     */
    private $totalSeconds;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $startLocation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stopLocation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(?\DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): self
    {
        $this->employee = $employee;

        return $this;
    }

    public function getTotalSeconds(): ?int
    {
        return $this->totalSeconds;
    }

    public function setTotalSeconds(?int $totalSeconds): self
    {
        $this->totalSeconds = $totalSeconds;

        return $this;
    }

    public function getStartLocation(): ?string
    {
        return $this->startLocation;
    }

    public function setStartLocation(?string $startLocation): self
    {
        $this->startLocation = $startLocation;

        return $this;
    }

    public function getStopLocation(): ?string
    {
        return $this->stopLocation;
    }

    public function setStopLocation(?string $stopLocation): self
    {
        $this->stopLocation = $stopLocation;

        return $this;
    }
}
