<?php

namespace App\CSV;

use App\Interfaces\CSV\CsvManagerInterface;
use App\Interfaces\CSV\ImportResultInterface;
use App\Repository\PersonRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;

final class CsvManager implements CsvManagerInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \SplFileInfo $filePath the CSV File path to import
     *
     * @return ImportResultInterface|null true or false according to import success/failure
     */
    public function import(\SplFileInfo $filePath, PersonRepository $personRepository,
                           ProjectRepository $projectRepository): ?ImportResultInterface
    {
        $con = $this->entityManager->getConnection();

        $persons = $personRepository->findAll();
        $projects = $projectRepository->findAll();

        $importResult = new ImportResult($persons, $projects);

        if (isset($con)) {
            return $importResult;
        }

        return null;
    }

}
