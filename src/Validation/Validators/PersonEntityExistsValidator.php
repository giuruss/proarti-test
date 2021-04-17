<?php


namespace App\Validation\Validators;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class PersonEntityExistsValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint)
    {
        // TODO: Implement validate() method.
    }
}
