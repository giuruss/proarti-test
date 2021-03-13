<?php

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
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="float")
     */
    private float $amount;

    /**
     * @ORM\ManyToMany(targetEntity=Person::class, mappedBy="projects")
     */
    private iterable $persons;

    /**
     * @ORM\OneToOne(targetEntity=Reward::class, inversedBy="project", cascade={"persist", "remove"})
     */
    private ?Reward $reward = null;

    public function __construct(string $name, float $amount)
    {
        $this->name = $name;
        $this->amount = $amount;
        $this->persons = new ArrayCollection();
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

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function getPersons(): iterable
    {
        return $this->persons;
    }

    public function addPerson(Person $person): void
    {
        if (!$this->persons->contains($person)) {
            $this->persons[] = $person;
            $person->addProject($this);
        }
    }

    public function removePerson(Person $person): void
    {
        if ($this->persons->removeElement($person)) {
            $person->removeProject($this);
        }
    }

    public function getReward(): ?Reward
    {
        return $this->reward;
    }

    public function setReward(?Reward $reward): void
    {
        $this->reward = $reward;
    }
}
