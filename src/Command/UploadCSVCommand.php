<?php

namespace App\Command;

use App\Entity\Person;
use App\Interfaces\CSV\CsvManagerInterface;
use App\Interfaces\Exceptions\BadColNameExceptionInterface;
use App\Repository\PersonRepository;
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
            $result = $this->upload->import(new SplFileObject($input->getArgument('csv_file_path')));
        } catch (BadColNameExceptionInterface $e) {
            $output->writeln($e->getColName().$e->getMessage());

            return Command::FAILURE;
        }

        $output->writeln('File uploaded');
        $output->writeln('Nb Person: '.$result->countPersons());
        $output->writeln('Nb Project: '.$result->countProjects());
        $output->writeln('Nb Donation: '.$result->countDonations());

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
        $person = new Person();

        $result = $this->personRepository->find('toto');
        if (null !== $result) {
            echo $result->getAmount();
        }
        foreach ($this->getDataFromFile($filePath) as $row) {
            $person->setFirstName($row['first_name']);
            $person->setLastName($row['last_name']);
            $person->setAmount($row['amount']);
            $person->setProjectName($row['project_name']);
            $person->setReward($row['reward']);
            $person->setRewardQuantity($row['reward_quantity']);
        }

        $this->entityManager->persist($person);

        $this->entityManager->flush();
    }
}
