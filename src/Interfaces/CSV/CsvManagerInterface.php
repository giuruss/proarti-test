<?php

namespace App\Interfaces\CSV;

use App\Interfaces\Exceptions\BadColNameExceptionInterface;
use App\Repository\PersonRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use SplFileInfo;

interface CsvManagerInterface
{
    /**
     * @throws BadColNameExceptionInterface
     */
    public function import(SplFileInfo $file, PersonRepository $personRepository,
                           ProjectRepository $projectRepository): ?ImportResultInterface;
}
