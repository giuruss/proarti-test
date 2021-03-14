<?php

namespace App\CSV;

use App\Interfaces\CSV\ImportResultInterface;

class ImportResult implements ImportResultInterface
{
    private iterable $persons;
    private iterable $donations;
    private iterable $rewards;
    private iterable $projects;

    public function __construct(iterable $persons, iterable $donations, iterable $rewards, iterable $projects)
    {
        $this->persons = $persons;
        $this->donations = $donations;
        $this->rewards = $rewards;
        $this->projects = $projects;
    }

    public function getPersons(): iterable
    {
        return $this->persons;
    }

    public function countPersons(): int
    {
        return \count([$this->persons]);
    }

    public function getProjects(): iterable
    {
        return $this->projects;
    }

    public function countProjects(): int
    {
        return \count([$this->projects]);
    }

    public function getDonations(): iterable
    {
        return $this->donations;
    }

    public function countDonations(): int
    {
        return \count([$this->getDonations()]);
    }
}
