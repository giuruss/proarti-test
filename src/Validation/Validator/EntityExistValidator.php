<?php

declare(strict_types=1);

namespace App\Validation\Validator;

use App\Validation\Constraints\EntityExist;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class EntityExistValidator extends ConstraintValidator
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof EntityExist) {
            throw new UnexpectedTypeException($constraint, EntityExist::class);
        }

        if (null === $value) {
            return;
        }

        $resultValue = $this->entityManager->getRepository($constraint->class)->count(['id' => $value]);

        if (0 === $resultValue) {
            $this->context->buildViolation($constraint->message)
                ->setCode(EntityExist::CODE)
                ->addViolation();
        }


    }
}
