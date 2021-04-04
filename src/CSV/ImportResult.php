<?php

namespace App\CSV;

use App\Entity\Donation;
use App\Entity\Person;
use App\Entity\Project;
use App\Entity\Reward;
use App\Interfaces\CSV\ImportResultInterface;

final class ImportResult implements ImportResultInterface
{
    private array $persons;
    private array $projects;
    private array $donations;
    private array $rewards;
    private iterable $errorCollectionTable;

    public function __construct (
        array $persons,
        array $projects,
        array $donations,
        array $rewards,
        iterable $errorCollectionTable
    ) {
        $this->persons = $persons;
        $this->projects = $projects;
        $this->donations = $donations;
        $this->rewards = $rewards;
        $this->errorCollectionTable = $errorCollectionTable;
    }

    /**
     * @return iterable<Person>
     */
    public function getPersons(): iterable
    {
        return $this->persons;
    }

    public function countPersons(): int
    {
        return \iterator_count(new \ArrayIterator($this->persons));
    }

    /**
     * @return iterable<Project>
     */
    public function getProjects(): iterable
    {
        return $this->projects;
    }

    public function countProjects(): int
    {
        return \iterator_count(new \ArrayIterator($this->projects));
    }

    /**
     * @return iterable<Reward>
     */
    public function getRewards(): iterable
    {
        return $this->rewards;
    }

    public function countRewards(): int
    {
        return \iterator_count(new \ArrayIterator($this->rewards));
    }

    /**
     * @return iterable<Donation>
     */
    public function getDonations(): iterable
    {
        return $this->donations;
    }

    public function countDonations(): int
    {
        return \iterator_count(new \ArrayIterator($this->donations));
    }

    /**
     * @return iterable<ErrorCollection>
     */
    public function getErrorCollectionTable(): iterable
    {
        return $this->errorCollectionTable;
    }
}
