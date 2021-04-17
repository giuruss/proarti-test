<?php

declare(strict_types=1);

namespace App\Interfaces\CSV;

use App\Entity\Donation;
use App\Entity\Person;
use App\Entity\Project;
use App\Entity\Reward;

interface DataImportManagerInterface
{
    public function importPerson(string $firstName, string $lastName): Person;

    public function importProject(string $projectName): Project;

    public function importReward(string $rewardName, int $rewardQuantity, Project $project): Reward;

    public function importDonation(int $amount, Person $person, Reward $reward): Donation;
}
