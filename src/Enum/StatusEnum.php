<?php

namespace App\Enum;

class StatusEnum
{
    public const TOTO = 'toto';
    public const TITI = 'titi';
    public const LIST = [
        self::TOTO,
        self::TITI,
    ];

    private string $value;

    /**
     * @throws EnumValueExceptionInterface
     */
    public function __construct(string $value)
    {
        self::validate($value);
        $this->value = $value;
    }

    /**
     * @throws EnumValueExceptionInterface
     */
    public static function validate(string $value): void
    {
        if (!\in_array($value, self::LIST, true)) {
            throw new EnumValueException($value, self::LIST);
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
