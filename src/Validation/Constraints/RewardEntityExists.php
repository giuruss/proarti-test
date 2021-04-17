<?php


namespace App\Validation\Constraints;


use App\Validation\Validators\RewardEntityExistsValidator;
use Symfony\Component\Validator\Constraint;

final class RewardEntityExists extends Constraint
{
    public string $message = 'Id not related to any Reward entity';

    public function validatedBy(): string
    {
        return RewardEntityExistsValidator::class;
    }

}
