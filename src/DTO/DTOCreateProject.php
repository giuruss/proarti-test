<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final class DTOCreateProject
{
    /**
     * @Assert\Type("string")
     * @Assert\NotBlank
     */
    public $projectName;
}
