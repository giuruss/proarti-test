<?php


namespace App\DTO;


use Symfony\Component\Validator\Constraints as Assert;

final class DTOImportData
{

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

    /**
     * @Assert\Type("string")
     * @Assert\NotBlank()
     */
    public $projectName;

    /**
     * @Assert\Type("string")
     * @Assert\NotBlank()
     */
    public $rewardName;

    /**
     * @Assert\Type("integer")
     * @Assert\Positive
     * @Assert\NotBlank()
     */
    public $rewardQuantity;

    /**
     * @Assert\Type("integer")
     * @Assert\Positive
     * @Assert\NotBlank()
     */
    public $donationAmount;


    public function __construct($firstName,
                                $lastName,
                                $projectName,
                                $rewardName,
                                $rewardQuantity,
                                $donationAmount
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->projectName = $projectName;
        $this->rewardName = $rewardName;
        $this->rewardQuantity = $rewardQuantity;
        $this->donationAmount = $donationAmount;
    }
}
