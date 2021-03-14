<?php

namespace App\CSV;

use App\Entity\Person;
use App\Entity\Project;
use App\Entity\Reward;
use App\Interfaces\CSV\CsvManagerInterface;
use App\Interfaces\CSV\ImportResultInterface;
use App\Repository\PersonRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class CsvManager implements CsvManagerInterface
{
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;
    private PersonRepository $personRepository;
    private ProjectRepository $projectRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        PersonRepository $personRepository,
        ProjectRepository $projectRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->personRepository = $personRepository;
        $this->projectRepository = $projectRepository;

    }

    /**
     * @param \SplFileInfo $filePath the CSV File path to import
     *
     * @return ImportResultInterface|null true or false according to import success/failure
     */
    public function import(\SplFileInfo $filePath): ?ImportResultInterface
    {
        $con = $this->entityManager->getConnection();

        $persons = $this->personRepository->findAll();
        $projects = $this->projectRepository->findAll();

        $importResult = new ImportResult($persons, $projects);

        if (isset($con)) {
            return $importResult;
        }

        return null;
    }

    private function getDataFromFile(string $filePath): array
    {
        $file = $filePath;

        /* @var string $fileString */
        $fileString = \file_get_contents($file);

        $data = $this->serializer->decode($fileString, 'csv', ['csv_delimiter' => ';']);

        if (\array_key_exists('results', $data)) {
            return $data['results'];
        }

        return $data;
    }

    public function createPerson(string $filePath): void
    {
//        $result = $this->personRepository->find('toto');
//
//        if (null !== $result) {
//            echo $result->getAmount();
//        }

        foreach ($this->getDataFromFile($filePath) as $row) {
            if (isset($row['first_name'], $row['last_name'])) {
                $person = new Person($row['first_name'], $row['last_name']);

                if (!$this->entityManager->contains($person)) {
                    $this->entityManager->persist($person);
                }
            }

            if (isset($row['project_name'], $row['amount'])) {
                $project = new Project($row['project_name'], $row['amount']);
                if (!$this->entityManager->contains($project)) {
                    $this->entityManager->persist($project);
                }
                if (isset($person)) {
                    $person->addProject($project);
                }
            }

            if (isset($row['reward'], $row['reward_quantity'])) {
                $reward = new Reward($row['reward'], $row['reward_quantity']);
                if (!$this->entityManager->contains($reward)) {
                    $this->entityManager->persist($reward);
                }
                if (isset($project)) {
                    $project->setReward($reward);
                }
            }
        }

        $this->entityManager->flush();
    }
}
