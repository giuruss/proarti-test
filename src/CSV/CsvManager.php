<?php

namespace App\CSV;

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
            if (isset($row['project_name'], $row['amount'])) {
                if (\is_int($row['amount'])) {
                    $project = new Project($row['project_name']);
                } else {
                    $project = new Project($row['project_name']);
                }

                if (!$this->entityManager->contains($project)) {
                    $this->entityManager->persist($project);
                }
            }

            if (isset($row['reward'], $row['reward_quantity'])) {
                if (\is_int($row['reward_quantity'])) {
                    $reward = new Reward($row['reward'], $row['reward_quantity']);
                } else {
                    $reward = new Reward($row['reward'], (int) $row['reward_quantity']);
                }

                if (!$this->entityManager->contains($reward)) {
                    $this->entityManager->persist($reward);
                }

                if (isset($project)) {
                    $reward->setProject($project);
                }
            }

            if (isset($row['first_name'], $row['last_name'])) {
                $person = new Person($row['first_name'], $row['last_name']);

                if (!$this->entityManager->contains($person)) {
                    $this->entityManager->persist($person);
                }

                if (isset($project)) {
                    $project->addPerson($person);
                }
            }
        }

        $this->entityManager->flush();
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
