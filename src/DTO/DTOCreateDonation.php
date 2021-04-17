<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Person;
use App\Entity\Reward;
use App\Validation\Constraints\EntityExist;
use Symfony\Component\Validator\Constraints as Assert;

final class DTOCreateDonation
{
    /**
     * @Assert\Type("integer")
     * @Assert\NotBlank
     */
    public $amount;

    /**
     * @Assert\Type("integer")
     * @Assert\NotNull
     * @EntityExist(class=Person::class)
     */
    public $personId;

    /**
     * @Assert\Type("integer")
     * @Assert\NotNull
     * @EntityExist(class=Reward::class)
     */
    public $rewardId;
}
