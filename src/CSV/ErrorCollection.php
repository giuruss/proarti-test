<?php


namespace App\CSV;


use Symfony\Component\Validator\ConstraintViolationList;

final class ErrorCollection
{
    private ConstraintViolationList $errorsViolationlist;
    private int $line;


    public function __construct(ConstraintViolationList $errorsViolationlist, int $line)
    {
        $this->errorsViolationlist = $errorsViolationlist;
        $this->line = $line;
    }

    public function getErrorsViolationlist(): ConstraintViolationList
    {
        return $this->errorsViolationlist;
    }

    public function getLine(): int
    {
        return $this->line;
    }
}
