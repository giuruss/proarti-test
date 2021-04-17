<?php

namespace App\DTO;

use App\Entity\Reward;
use App\Validator\EntityExist;
use Symfony\Component\Validator\Constraints as Assert;

final class DTOCreateProject
{
    /**
     * @Assert\Type("string")
     * @Assert\NotBlank()
     */
    public $projectName;

    /**
     * @Assert\Type("integer")
     * @EntityExist(class=Reward::class)
     */
    public $rewardId;
}
