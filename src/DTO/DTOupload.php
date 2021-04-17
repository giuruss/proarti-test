<?php

declare(strict_types=1);

namespace App\DTO;

class DTOupload
{
    private string $fileName;

    final public function getFileName(): string
    {
        return $this->fileName;
    }

    final public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }
}
