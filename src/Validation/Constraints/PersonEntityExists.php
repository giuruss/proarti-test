<?php


namespace App\Validation\Constraints;


use App\Validation\Validators\PersonEntityExistsValidator;
use Symfony\Component\Validator\Constraint;

final class PersonEntityExists extends Constraint
{
    public string $message = 'Id not related to any Person entity';

    public function validatedBy(): string
    {
        return PersonEntityExistsValidator::class;
    }

}
