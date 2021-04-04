<?php

namespace App\Command;

use App\CSV\ErrorCollection;
use App\Interfaces\CSV\CsvManagerInterface;
use SplFileObject;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class UploadCSVCommand extends Command
{
    protected static $defaultName = 'app:upload-csv';
    protected CsvManagerInterface $csvManagerInterface;

    public function __construct(
        CsvManagerInterface $csvManagerInterface,
    ) {
        parent::__construct();
        $this->csvManagerInterface = $csvManagerInterface;
    }

    protected function configure(): void
    {
        $this->setDescription('Uploads a csv file');
        $this->setHelp('This command allows you to import a csv file');
        $this->addArgument('csv_file_path', InputArgument::REQUIRED, 'csv file path.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $result = $this->csvManagerInterface->import(new SplFileObject($input->getArgument('csv_file_path')));

        $output->writeln('File uploaded');
        $output->writeln('Nb Person: '.$result->countPersons());
        $output->writeln('Nb Project: '.$result->countProjects());
        $output->writeln('Nb Donation: '.$result->countDonations());
        $output->writeln('Nb Reward: '.$result->countRewards());
        $output->writeln('');

        if(!empty($result->getErrorCollectionTable())) {
            foreach ($result->getErrorCollectionTable() as $key => $errors){
                assert($errors instanceof ErrorCollection);
                foreach ($errors->getErrorsViolationlistInterface() as $error) {
                    $output->writeln('Ligne (non importée) no : '.$errors->getLine() + 1);
                    $output->writeln('Donnée liée à : '.$error->getPropertyPath());
                    $output->writeln('Donnée concernée : '.$error->getInvalidValue());
                    $output->writeln('Erreur : '.$error->getMessage());
                    $output->writeln('');
                }
            }
        }

        return Command::SUCCESS;
    }
}
