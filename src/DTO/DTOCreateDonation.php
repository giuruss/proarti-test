<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

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
