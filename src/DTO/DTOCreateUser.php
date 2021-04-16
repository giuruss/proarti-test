<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

final class DTOCreateUser
{

    /**
     * @Assert\Type("integer")
     * @Assert\NotNull()
     * @EntityExist(class=User::class)
     */
    public $userId;

    /**
     * @Assert\Type("string")
     * @Assert\NotBlank()
     */
    public $firstName;

    /**
     * @Assert\Type("string")
     * @Assert\NotBlank()
     */
    public $lastName;

}
