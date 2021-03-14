<?php

namespace App\Command;

use App\CSV\CsvManager;
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
    private CsvManager $csvManager;

    public function __construct(
        CsvManagerInterface $upload,
        CsvManager $csvManager
    ) {
        parent::__construct();
        $this->upload = $upload;
        $this->csvManager = $csvManager;
    }

    protected function configure(): void
    {
        $this->setDescription('Uploads a csv file');

        $this->setHelp('This command allows you to import a csv file');

        $this->addArgument('csv_file_path', InputArgument::REQUIRED, 'csv file path.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->csvManager->createPerson($input->getArgument('csv_file_path'));

        try {
            $result = $this->upload->import(new SplFileObject($input->getArgument('csv_file_path')));
        } catch (BadColNameExceptionInterface $e) {
            $output->writeln($e->getColName().$e->getMessage());

            return Command::FAILURE;
        }

        if (null !== $result) {
            $output->writeln('File uploaded');
            $output->writeln('Nb Person: '.$result->countPersons());
            $output->writeln('Nb Project: '.$result->countProjects());
            $output->writeln('Nb Donation: '.$result->countDonations());
        }

        return Command::SUCCESS;
    }


}
