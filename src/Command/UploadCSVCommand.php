<?php

namespace App\Command;

use App\Entity\Person;
use App\Entity\Project;
use App\Entity\Reward;
use App\Interfaces\CSV\CsvManagerInterface;
use App\Interfaces\Exceptions\BadColNameExceptionInterface;
use App\Repository\PersonRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use SplFileObject;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class UploadCSVCommand extends Command
{
    protected static $defaultName = 'app:upload-csv';

    protected CsvManagerInterface $upload;

    private EntityManagerInterface $entityManager;

    private PersonRepository $personRepository;
    
    private ProjectRepository $projectRepository;

    private SerializerInterface $serializer;

    public function __construct(
        CsvManagerInterface $upload,
        EntityManagerInterface $entityManager,
        PersonRepository $personRepository,
        SerializerInterface $serializer
    ) {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->personRepository = $personRepository;
        $this->upload = $upload;
        $this->serializer = $serializer;
    }

    protected function configure(): void
    {
        $this->setDescription('Uploads a csv file');

        $this->setHelp('This command allows you to import a csv file');

        $this->addArgument('csv_file_path', InputArgument::REQUIRED, 'csv file path.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->createPerson($input->getArgument('csv_file_path'));

        try {
            $result = $this->upload->import(
                new SplFileObject($input->getArgument('csv_file_path')),
                $this->personRepository,
                $this->projectRepository
            );

        } catch (BadColNameExceptionInterface $e) {
            $output->writeln($e->getColName().$e->getMessage());

            return Command::FAILURE;
        }

        if (null !== $result) {
            $output->writeln('File uploaded');
            $output->writeln('Nb Person: ' . $result->countPersons());
            $output->writeln('Nb Project: ' . $result->countProjects());
            $output->writeln('Nb Donation: ' . $result->countDonations());
        }

        return Command::SUCCESS;
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

    private function createPerson(string $filePath): void
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
