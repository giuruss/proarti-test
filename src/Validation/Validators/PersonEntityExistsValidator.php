<?php


namespace App\Validation\Validators;


use App\Entity\Person;
use App\Repository\PersonRepository;
use App\Validation\Constraints\PersonEntityExists;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class PersonEntityExistsValidator extends ConstraintValidator
{
    private PersonRepository $personRepository;

    public function __construct(PersonRepository $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof PersonEntityExists) {
            throw new UnexpectedTypeException($constraint, PersonEntityExists::class);
        }

        if (null === $value) {
            return;
        }

        $value = $this->personRepository->find($value);

        if (!$value instanceof Person) {
            throw new UnexpectedTypeException($value, Person::class);
        }
    }
}
