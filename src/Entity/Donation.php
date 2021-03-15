<?php

/** @noinspection MethodShouldBeFinalInspection */

namespace App\Entity;

use App\Repository\DonationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DonationRepository::class)
 */
class Donation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="integer")
     */
    private int $amount;

    /**
     * @ORM\ManyToOne(targetEntity=Person::class, inversedBy="donations")
     * @ORM\JoinColumn(nullable=false)
     */
    private Person $person;

    /**
     * @ORM\ManyToOne(targetEntity=Reward::class, inversedBy="donations")
     * @ORM\JoinColumn(nullable=false)
     */
    private Reward $reward;

    public function __construct(int $amount, Person $person, Reward $reward)
    {
        $this->amount = $amount;
        $this->person = $person;
        $this->reward = $reward;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getPerson(): Person
    {
        return $this->person;
    }

    public function setPerson(Person $person): void
    {
        $this->person = $person;
    }

    public function getReward(): Reward
    {
        return $this->reward;
    }

    public function setReward(Reward $reward): void
    {
        $this->reward = $reward;
    }
}
