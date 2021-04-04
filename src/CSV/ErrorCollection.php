<?php


namespace App\CSV;


use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class ErrorCollection
{
    private ConstraintViolationListInterface $errorsViolationlistInterface;
    private int $line;


    public function __construct(ConstraintViolationListInterface $errorsViolationlistInterface, int $line)
    {
        $this->errorsViolationlistInterface = $errorsViolationlistInterface;
        $this->line = $line;
    }

    public function getErrorsViolationlistInterface(): ConstraintViolationListInterface
    {
        return $this->errorsViolationlistInterface;
    }

    public function getLine(): int
    {
        return $this->line;
    }
}
