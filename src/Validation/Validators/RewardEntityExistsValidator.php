<?php


namespace App\Validation\Validators;


use App\Entity\Reward;
use App\Repository\RewardRepository;
use App\Validation\Constraints\RewardEntityExists;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class RewardEntityExistsValidator extends ConstraintValidator
{
    private RewardRepository $rewardRepository;

    public function __construct(RewardRepository $rewardRepository)
    {
        $this->rewardRepository = $rewardRepository;
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof RewardEntityExists) {
            throw new UnexpectedTypeException($constraint, RewardEntityExists::class);
        }

        if (null === $value) {
            return;
        }

        $value = $this->rewardRepository->find($value);

        if (!$value instanceof Reward) {
            throw new UnexpectedTypeException($value, Reward::class);
        }
    }
}
