<?php

/** @noinspection MethodShouldBeFinalInspection */

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 */
class Project
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
     * @ORM\OneToMany(targetEntity=Reward::class, mappedBy="project", orphanRemoval=true)
     */
    private iterable $rewards;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->rewards = new ArrayCollection();
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

    /**
     * @return iterable<Reward>
     */
    public function getRewards(): iterable
    {
        return $this->rewards;
    }

    public function addReward(Reward $reward): void
    {
        if (!$this->rewards->contains($reward)) {
            $this->rewards[] = $reward;
            $reward->setProject($this);
        }
    }

    public function removeReward(Reward $reward): void
    {
        if ($this->rewards->removeElement($reward)) {
            if ($reward->getProject() === $this) {
                $reward->setProject(null);
            }
        }
    }
}
