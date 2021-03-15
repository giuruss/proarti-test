<?php

namespace App\Interfaces\CSV;

use SplFileInfo;

interface CsvManagerInterface
{
    public function import(SplFileInfo $file): ImportResultInterface;
}
