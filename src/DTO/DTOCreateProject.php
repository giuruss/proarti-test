<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final class DTOCreateProject
{
    /**
     * @Assert\Type("string")
     * @Assert\NotBlank()
     */
    public $projectName;

    /**
     * @Assert\Type("Reward")
     */
    public $reward;
}
