<?php


namespace App\Validator;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class EntityExist extends Constraint
{
    public $message = 'Id not related to any entity';

    public function validatedBy(): string
    {
        return static::class.'Validator';
    }
}
