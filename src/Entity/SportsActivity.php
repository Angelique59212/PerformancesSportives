<?php

namespace App\Entity;

use App\Repository\SportsActivityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SportsActivityRepository::class)]
class SportsActivity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getSportsActivity'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['getSportsActivity'])]
    #[Assert\Positive]
    private ?string $duration = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['getSportsActivity'])]
    #[Assert\Positive]
    private ?string $distanceTraveled = null;

    #[ORM\Column]
    #[Groups(['getSportsActivity'])]
    #[Assert\Positive]
    private ?float $burntCalories = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['getSportsActivity'])]
    /**
     * @var string  "Y-m-d H:i:s" formatted value
     */
    #[Assert\DateTime]
    private ?\DateTimeInterface $dateActivity = null;

    #[ORM\ManyToOne(inversedBy: 'activity')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'sportsActivities')]
    private ?TypeActivity $typeActivity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(string $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDistanceTraveled(): ?string
    {
        return $this->distanceTraveled;
    }

    public function setDistanceTraveled(?string $distanceTraveled): static
    {
        $this->distanceTraveled = $distanceTraveled;

        return $this;
    }

    public function getBurntCalories(): ?float
    {
        return $this->burntCalories;
    }

    public function setBurntCalories(float $burntCalories): static
    {
        $this->burntCalories = $burntCalories;

        return $this;
    }

    public function getDateActivity(): ?\DateTimeInterface
    {
        return $this->dateActivity;
    }

    public function setDateActivity(\DateTimeInterface $dateActivity): static
    {
        $this->dateActivity = $dateActivity;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getTypeActivity(): ?TypeActivity
    {
        return $this->typeActivity;
    }

    public function setTypeActivity(?TypeActivity $typeActivity): static
    {
        $this->typeActivity = $typeActivity;

        return $this;
    }
}
