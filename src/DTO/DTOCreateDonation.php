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
     * @Assert\Type("Person")
     */
    public $person;

    /**
     * @Assert\Type("Reward")
     */
    public $reward;
}
