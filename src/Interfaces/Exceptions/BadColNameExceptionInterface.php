<?php

namespace App\Interfaces\Exceptions;

interface BadColNameExceptionInterface extends \Throwable
{
    public function getColName(): string;
}
