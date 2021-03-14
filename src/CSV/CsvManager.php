<?php

namespace App\CSV;

use App\Entity\Donation;
use App\Entity\Person;
use App\Entity\Project;
use App\Entity\Reward;
use App\Interfaces\CSV\CsvManagerInterface;
use App\Interfaces\CSV\ImportResultInterface;
use App\Repository\DonationRepository;
use App\Repository\PersonRepository;
use App\Repository\ProjectRepository;
use App\Repository\RewardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class CsvManager implements CsvManagerInterface
{
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;
    private PersonRepository $personRepository;
    private DonationRepository $donationRepository;
    private ProjectRepository $projectRepository;
    private RewardRepository $rewardRepository;


    public function __construct(
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        PersonRepository $personRepository,
        DonationRepository $donationRepository,
        ProjectRepository $projectRepository,
        RewardRepository $rewardRepository
    ) {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->personRepository = $personRepository;
        $this->donationRepository = $donationRepository;
        $this->projectRepository = $projectRepository;
        $this->rewardRepository = $rewardRepository;
    }

    /**
     * @param \SplFileInfo $filePath the CSV File path to import
     *
     * @return ImportResultInterface true or false according to import success/failure
     */
    public function import(\SplFileInfo $filePath): ImportResultInterface
    {
        $persons = $this->personRepository->findAll();
        $donations = $this->donationRepository->findAll();
        $projects = $this->projectRepository->findAll();
        $rewards = $this->rewardRepository->findAll();

        return new ImportResult($persons, $donations, $rewards, $projects);
    }



    public function createPerson(string $filePath): void
    {
        foreach ($this->getDataFromFile($filePath) as $row) {

            if (isset($row['first_name'], $row['last_name'])) {

                $person = $this->personRepository->findOneBy(['firstName' => $row['first_name'], 'lastName' => $row['last_name']]);

                if (null === $person) {
                    $person = new Person($row['first_name'], $row['last_name']);
                    $this->entityManager->persist($person);
                }

            }

            if (isset($row['project_name'])) {

                $project = $this->projectRepository->findOneBy(['name' => $row['project_name']]);

                if (null === $project) {
                    $project = new Project($row['project_name']);
                    $this->entityManager->persist($project);
                }

            }

            if (isset($row['reward'])) {

                $reward = $this->rewardRepository
                    ->findOneBy(['name' => $row['reward'], 'quantity' => $row['reward_quantity']]);

                if (null === $reward) {
                    if (\is_int($row['reward_quantity'])) {
                        $reward = new Reward($row['reward'], $row['reward_quantity'], $project);
                    } else {
                        $reward = new Reward($row['reward'], (int)($row['reward_quantity']), $project);
                    }
                    $this->entityManager->persist($reward);
                }
            }

            if (isset($row['amount'])) {

                $donation = $this->donationRepository->findOneBy(['amount' => $row['amount']]);

                if (null === $donation) {
                    if (\is_int($row['amount'])) {
                        $donation = new Donation($row['amount'], $person, $reward);
                    } else {
                        $donation = new Donation((int)($row['amount']), $person, $reward);
                    }
                    $this->entityManager->persist($donation);
                }
            }

            $this->entityManager->flush();
        }

    }

    private function getDataFromFile(string $filePath): array
    {
        $file = $filePath;

        /* @var string $fileString */
        $fileString = \file_get_contents($file);

        $data = $this->serializer->decode($fileString, 'csv', ['csv_delimiter' => ',']);

        if (\array_key_exists('results', $data)) {
            return $data['results'];
        }

        return $data;
    }
}
