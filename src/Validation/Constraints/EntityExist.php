<?php

declare(strict_types=1);

namespace App\Validation\Constraints;

use App\Validation\Validator\EntityExistValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
final class EntityExist extends Constraint
{
    public string $message = 'Id not related to any entity';

    public string $class;

    public const CODE = 'ENTITY_EXISTS';

    /**
     * {@inheritdoc}
     *
     * @param string|array $class One ore multiple types to validate against or a set of options
     */
    public function __construct($class, string $message = null, array $groups = null, $payload = null, array $options = [])
    {
        if (\is_array($class) && \is_string(\key($class))) {
            $options = \array_merge($class, $options);
        } elseif (null !== $class) {
            $options['value'] = $class;
        }

        parent::__construct($options, $groups, $payload);

        $this->message = $message ?? $this->message;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOption(): string
    {
        return 'class';
    }

    /**
     * {@inheritdoc}
     */
    public function getRequiredOptions(): array
    {
        return ['class'];
    }

    public function validatedBy(): string
    {
        return EntityExistValidator::class;
    }
}
