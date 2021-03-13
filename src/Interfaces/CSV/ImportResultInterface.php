<?php

namespace App\Interfaces\CSV;

use App\Entity\Person;
use App\Entity\Project;

interface ImportResultInterface
{
    /**
     * @return iterable<Person>
     */
    public function getPersons(): iterable;

    public function countPersons(): int;

    /**
     * @return iterable<Project>
     */
    public function getProjects(): iterable;

    public function countProjects(): int;

    /**
     * @return iterable<Donation>
     */
    public function getDonations(): iterable;

    public function countDonations(): int;
}
