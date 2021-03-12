<?php

namespace App\CSV;

use App\Interfaces\CSV\CsvManagerInterface;
use App\Interfaces\CSV\ImportResultInterface;
use Doctrine\ORM\EntityManagerInterface;

final class CsvManager implements CsvManagerInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $filePath the CSV File path to import
     *
     * @return bool true or false according to import success/failure
     */
    public function import(\SplFileInfo $filePath): ImportResultInterface
    {
        $con = $this->entityManager->getConnection();

        if (isset($con)) {
            return true;
        }

        return false;
    }
}
