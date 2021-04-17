<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Person;
use App\Entity\Reward;
use App\Validation\Constraints\PersonEntityExists;
use App\Validation\Constraints\RewardEntityExists;

final class DTOCreateDonation
{

    /**
     * @Assert\Type("integer")
     * @Assert\NotBlank()
     */
    public $amount;

    /**
     * @Assert\Type("integer")
     * @Assert\NotNull()
     * @PersonEntityExists(class=Person::class)
     */
    public $personId;

    /**
     * @Assert\Type("integer")
     * @Assert\NotNull()
     * @RewardEntityExists(class=Reward::class)
     */
    public $rewardId;
}
