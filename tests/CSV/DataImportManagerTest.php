<?php

namespace App\Tests\CSV;

use App\CSV\DataImportManager;
use App\Entity\Donation;
use App\Entity\Person;
use App\Entity\Project;
use App\Entity\Reward;
use App\Interfaces\Exceptions\EntityNotFoundExceptionInterface;
use App\Interfaces\Gateways\DonationGatewayInterface;
use App\Interfaces\Gateways\PersonGatewayInterface;
use App\Interfaces\Gateways\ProjectGatewayInterface;
use App\Interfaces\Gateways\RewardGatewayInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class DataImportManagerTest extends TestCase
{
    final public function testImportPersonExists(): void
    {
        $personGatewayInterface = $this->prophesize(PersonGatewayInterface::class);
        $projectGatewayInterface = $this->prophesize(ProjectGatewayInterface::class);
        $rewardGatewayInterface = $this->prophesize(RewardGatewayInterface::class);
        $donationGatewayInterface = $this->prophesize(DonationGatewayInterface::class);
        $person = $this->prophesize(Person::class);

        $personGatewayInterface->findByFirstAndLastName('Jean', 'Dupont')->willReturn($person->reveal())->shouldBeCalledOnce();

        $csvManager = new DataImportManager(
            $personGatewayInterface->reveal(),
            $projectGatewayInterface->reveal(),
            $rewardGatewayInterface->reveal(),
            $donationGatewayInterface->reveal(),
        );

        $result = $csvManager->importPerson('Jean', 'Dupont');

        self::assertSame($person->reveal(), $result);
    }

    final public function testImportPerson(): void
    {
        $personGatewayInterface = $this->prophesize(PersonGatewayInterface::class);
        $projectGatewayInterface = $this->prophesize(ProjectGatewayInterface::class);
        $rewardGatewayInterface = $this->prophesize(RewardGatewayInterface::class);
        $donationGatewayInterface = $this->prophesize(DonationGatewayInterface::class);
        $exception = $this->prophesize(EntityNotFoundExceptionInterface::class);

        $personGatewayInterface->findByFirstAndLastName('Jean', 'Dupont')->willThrow($exception->reveal())->shouldBeCalledOnce();

        $personGatewayInterface->persist(Argument::that(function (Person $item): bool {
            self::assertSame('Jean', $item->getFirstName());
            self::assertSame('Dupont', $item->getLastName());

            return true;
        }))->shouldBeCalledOnce();

        $csvManager = new DataImportManager(
            $personGatewayInterface->reveal(),
            $projectGatewayInterface->reveal(),
            $rewardGatewayInterface->reveal(),
            $donationGatewayInterface->reveal(),
        );

        $result = $csvManager->importPerson('jean', 'dupont');

        self::assertSame('Jean', $result->getFirstName());
        self::assertSame('Dupont', $result->getLastName());
        self::assertCount(0, $result->getDonations());
    }

    final public function testImportProjectExists(): void
    {
        $personGatewayInterface = $this->prophesize(PersonGatewayInterface::class);
        $projectGatewayInterface = $this->prophesize(ProjectGatewayInterface::class);
        $rewardGatewayInterface = $this->prophesize(RewardGatewayInterface::class);
        $donationGatewayInterface = $this->prophesize(DonationGatewayInterface::class);
        $project = $this->prophesize(Project::class);

        $projectGatewayInterface->findByName('projet_test')->willReturn($project->reveal())->shouldBeCalledOnce();

        $csvManager = new DataImportManager(
            $personGatewayInterface->reveal(),
            $projectGatewayInterface->reveal(),
            $rewardGatewayInterface->reveal(),
            $donationGatewayInterface->reveal(),
        );

        $result = $csvManager->importProject('projet_test');

        self::assertSame($project->reveal(), $result);
    }

    final public function testImportProject(): void
    {
        $personGatewayInterface = $this->prophesize(PersonGatewayInterface::class);
        $projectGatewayInterface = $this->prophesize(ProjectGatewayInterface::class);
        $rewardGatewayInterface = $this->prophesize(RewardGatewayInterface::class);
        $donationGatewayInterface = $this->prophesize(DonationGatewayInterface::class);
        $exception = $this->prophesize(EntityNotFoundExceptionInterface::class);

        $projectGatewayInterface->findByName('projet_test')->willThrow($exception->reveal())->shouldBeCalledOnce();

        $projectGatewayInterface->persist(Argument::that(function (Project $item): bool {
            self::assertSame('projet_test', $item->getName());

            return true;
        }))->shouldBeCalledOnce();

        $csvManager = new DataImportManager(
            $personGatewayInterface->reveal(),
            $projectGatewayInterface->reveal(),
            $rewardGatewayInterface->reveal(),
            $donationGatewayInterface->reveal(),
        );

        $result = $csvManager->importProject('projet_test');

        self::assertSame('projet_test', $result->getName());
        self::assertCount(0, $result->getRewards());
    }

    final public function testImportRewardExists(): void
    {
        $personGatewayInterface = $this->prophesize(PersonGatewayInterface::class);
        $projectGatewayInterface = $this->prophesize(ProjectGatewayInterface::class);
        $rewardGatewayInterface = $this->prophesize(RewardGatewayInterface::class);
        $donationGatewayInterface = $this->prophesize(DonationGatewayInterface::class);
        $rewardQuantity = 1;
        $project = new Project('projet_test');
        $reward = $this->prophesize(Reward::class);

        $rewardGatewayInterface->findByName('reward_name')->willReturn($reward->reveal())->shouldBeCalledOnce();

        $csvManager = new DataImportManager(
            $personGatewayInterface->reveal(),
            $projectGatewayInterface->reveal(),
            $rewardGatewayInterface->reveal(),
            $donationGatewayInterface->reveal(),
        );

        $result = $csvManager->importReward('reward_name', $rewardQuantity, $project);

        self::assertSame($reward->reveal(), $result);
    }

    final public function testImportReward(): void
    {
        $personGatewayInterface = $this->prophesize(PersonGatewayInterface::class);
        $projectGatewayInterface = $this->prophesize(ProjectGatewayInterface::class);
        $rewardGatewayInterface = $this->prophesize(RewardGatewayInterface::class);
        $donationGatewayInterface = $this->prophesize(DonationGatewayInterface::class);
        $exception = $this->prophesize(EntityNotFoundExceptionInterface::class);
        $rewardQuantity = 1;
        $project = new Project('projet_test');

        $rewardGatewayInterface->findByName('reward_name')->willThrow($exception->reveal())->shouldBeCalledOnce();

        $rewardGatewayInterface->persist(Argument::type(Reward::class))->shouldBeCalledOnce();

        $csvManager = new DataImportManager(
            $personGatewayInterface->reveal(),
            $projectGatewayInterface->reveal(),
            $rewardGatewayInterface->reveal(),
            $donationGatewayInterface->reveal(),
        );

        $result = $csvManager->importReward('reward_name', $rewardQuantity, $project);

        self::assertSame('reward_name', $result->getName());
        self::assertCount(0, $result->getDonations());
        self::assertEquals($project, $result->getProject());
        self::assertSame('projet_test', $result->getProject()->getName());
    }

    final public function testImportDonation(): void
    {
        $personGatewayInterface = $this->prophesize(PersonGatewayInterface::class);
        $projectGatewayInterface = $this->prophesize(ProjectGatewayInterface::class);
        $rewardGatewayInterface = $this->prophesize(RewardGatewayInterface::class);
        $donationGatewayInterface = $this->prophesize(DonationGatewayInterface::class);

        $amount = 10;
        $project = new Project('projet_test');
        $person = new Person('person_first_name_test', 'person_last_name_test');
        $reward = new Reward('reward_test', 50, $project);

        $donationGatewayInterface->persist(Argument::type(Donation::class))->shouldBeCalledOnce();

        $csvManager = new DataImportManager(
            $personGatewayInterface->reveal(),
            $projectGatewayInterface->reveal(),
            $rewardGatewayInterface->reveal(),
            $donationGatewayInterface->reveal(),
        );

        $result = $csvManager->importDonation($amount, $person, $reward);

        self::assertSame($amount, $result->getAmount());
        self::assertSame($person, $result->getPerson());
        self::assertSame($reward, $result->getReward());
        self::assertSame($person->getFirstName(), $result->getPerson()->getFirstName());
    }
}
