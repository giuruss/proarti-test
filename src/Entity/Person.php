<?php

/** @noinspection MethodShouldBeFinalInspection */

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PersonRepository::class)
 */
class Person
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
    private string $firstName;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private string $lastName;

    /**
     * @ORM\OneToMany(targetEntity=Donation::class, mappedBy="person", orphanRemoval=true)
     */
    private iterable $donations;

    public function __construct(string $firstName, string $lastName)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->donations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
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
            $donation->setPerson($this);
        }
    }

    public function removeDonation(Donation $donation): void
    {
        if ($this->donations->removeElement($donation)) {
            // set the owning side to null (unless already changed)
            if ($donation->getPerson() === $this) {
                $donation->setPerson(null);
            }
        }
    }
}
