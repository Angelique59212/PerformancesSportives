<?php

namespace App\Entity;

use App\Repository\TypeActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TypeActivityRepository::class)]
class TypeActivity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getTypeActivity', 'getSportsActivity'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['getTypeActivity', 'getSportsActivity'])]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'typeActivity', targetEntity: SportsActivity::class)]
    private Collection $sportsActivities;

    public function __construct()
    {
        $this->sportsActivities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, SportsActivity>
     */
    public function getSportsActivities(): Collection
    {
        return $this->sportsActivities;
    }

    public function addSportsActivity(SportsActivity $sportsActivity): static
    {
        if (!$this->sportsActivities->contains($sportsActivity)) {
            $this->sportsActivities->add($sportsActivity);
            $sportsActivity->setTypeActivity($this);
        }

        return $this;
    }

    public function removeSportsActivity(SportsActivity $sportsActivity): static
    {
        if ($this->sportsActivities->removeElement($sportsActivity)) {
            // set the owning side to null (unless already changed)
            if ($sportsActivity->getTypeActivity() === $this) {
                $sportsActivity->setTypeActivity(null);
            }
        }

        return $this;
    }
}
