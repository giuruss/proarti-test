<?php

declare(strict_types=1);

namespace App\CSV;

use App\Entity\Donation;
use App\Entity\Person;
use App\Entity\Project;
use App\Entity\Reward;
use App\Interfaces\CSV\DataImportManagerInterface;
use App\Interfaces\Exceptions\EntityNotFoundExceptionInterface;
use App\Interfaces\Gateways\DonationGatewayInterface;
use App\Interfaces\Gateways\PersonGatewayInterface;
use App\Interfaces\Gateways\ProjectGatewayInterface;
use App\Interfaces\Gateways\RewardGatewayInterface;

final class DataImportManager implements DataImportManagerInterface
{
    private PersonGatewayInterface $personGatewayInterface;
    private ProjectGatewayInterface $projectGatewayInterface;
    private RewardGatewayInterface $rewardGatewayInterface;
    private DonationGatewayInterface $donationGatewayInterface;

    public function __construct(
        PersonGatewayInterface $personGatewayInterface,
        ProjectGatewayInterface $projectGatewayInterface,
        RewardGatewayInterface $rewardGatewayInterface,
        DonationGatewayInterface $donationGatewayInterface
    ) {
        $this->personGatewayInterface = $personGatewayInterface;
        $this->projectGatewayInterface = $projectGatewayInterface;
        $this->rewardGatewayInterface = $rewardGatewayInterface;
        $this->donationGatewayInterface = $donationGatewayInterface;
    }

    public function importPerson(string $firstName, string $lastName): Person
    {
        $firstNameValue = \ucfirst(\trim($firstName));
        $lastNameValue = \ucfirst(\trim($lastName));

        try {
            return $this->personGatewayInterface->findByFirstAndLastName($firstNameValue, $lastNameValue);
        } catch (EntityNotFoundExceptionInterface $e) {
            $person = new Person(
                $firstNameValue,
                $lastNameValue,
            );

            echo 'Concerned entity : '.$e->getClass().' ';

            $this->personGatewayInterface->persist($person);

            return $person;
        }
    }

    public function importProject(string $projectName): Project
    {
        $projectNameValue = \trim($projectName);
        try {
            return $this->projectGatewayInterface->findByName($projectNameValue);
        } catch (EntityNotFoundExceptionInterface $e) {
            $project = new Project($projectNameValue);
            echo $e->getMessage()."\n";
            $this->projectGatewayInterface->persist($project);

            return $project;
        }
    }

    public function importReward(string $rewardName, int $rewardQuantity, Project $project): Reward
    {
        $projectNameValue = \trim($rewardName);

        try {
            $reward = $this->rewardGatewayInterface->findByName($projectNameValue);
            $reward->setProject($project);
            $reward->setQuantity($rewardQuantity);

            return $reward;
        } catch (EntityNotFoundExceptionInterface $e) {
            $reward = new Reward($projectNameValue, $rewardQuantity, $project);
            echo $e->getMessage()."\n";
            $this->rewardGatewayInterface->persist($reward);

            return $reward;
        }
    }

    public function importDonation(int $amount, Person $person, Reward $reward): Donation
    {
        $donation = new Donation($amount, $person, $reward);
        $this->donationGatewayInterface->persist($donation);

        return $donation;
    }
}
