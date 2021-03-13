<?php


namespace App\CSV;


use App\Entity\Project;
use App\Interfaces\CSV\ImportResultInterface;

class ImportResult implements ImportResultInterface
{

    private iterable $persons;

    private iterable $projects;

    public function __construct(iterable $persons, iterable $projects)
    {
        $this->persons = $persons;
        $this->projects = $projects;
    }


    public function getPersons(): iterable
    {
        return $this->persons;
    }

    public function countPersons(): int
    {
        return count([$this->persons]);
    }

    public function getProjects(): iterable
    {
        return $this->projects;
    }

    public function countProjects(): int
    {
        return count([$this->projects]);
    }

    public function getDonations(): iterable
    {
        $donations = [];

        foreach ($this->projects as $project) {
            assert($project instanceof Project);
            $donations[] = $project->getAmount();
        }
        return $donations;
    }

    public function countDonations(): int
    {
        return count([$this->getDonations()]);
    }
}