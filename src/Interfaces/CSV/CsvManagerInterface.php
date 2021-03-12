<?php

namespace App\Interfaces\CSV;

use App\Interfaces\Exceptions\BadColNameExceptionInterface;
use SplFileInfo;

interface CsvManagerInterface
{
    /**
     * @throws BadColNameExceptionInterface
     */
    public function import(SplFileInfo $file): ImportResultInterface;
}
