<?php

declare(strict_types=1);

/** @noinspection MethodShouldBeFinalInspection */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RewardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RewardRepository::class)
 */
class Reward
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private string $name;

    /**
     * @ORM\Column(type="integer")
     */
    private int $quantity;

    /**
     * @ORM\OneToMany(targetEntity=Donation::class, mappedBy="reward", orphanRemoval=true)
     */
    private iterable $donations;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="rewards")
     * @ORM\JoinColumn(nullable=false)
     */
    private Project $project;

    public function __construct(string $name, int $quantity, Project $project)
    {
        $this->name = $name;
        $this->quantity = $quantity;
        $this->donations = new ArrayCollection();
        $this->project = $project;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return iterable<Donation>
     */
    public function getDonations(): iterable
    {
        return $this->donations;
    }

    public function addDonation(Donation $donation): void
    {
        if (!$this->donations->contains($donation)) {
            $this->donations[] = $donation;
            $donation->setReward($this);
        }
    }

    public function removeDonation(Donation $donation): void
    {
        if ($this->donations->removeElement($donation)) {
            if ($donation->getReward() === $this) {
                $donation->setReward(null);
            }
        }
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function setProject(Project $project): void
    {
        $this->project = $project;
    }
}
