<?php

declare(strict_types=1);

namespace App\Interfaces\CSV;

use SplFileInfo;

interface CsvManagerInterface
{
    public function import(SplFileInfo $file): ImportResultInterface;
}
