<?php


namespace App\DataTransformer;


use ApiPlatform\Core\DataTransformer\DataTransformerInitializerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\DTO\DTOCreatePerson;
use App\Entity\Person;

final class PersonInputDataTransformer implements DataTransformerInitializerInterface
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function transform($object, string $to, array $context = []): Person
    {
        assert($object instanceof DTOCreatePerson);
        $this->validator->validate($object);

        $person = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE];
        if (null === $person) {
            return new Person($object->firstName, $object->lastName);
        }

        assert($person instanceof Person);

        $person->setFirstName($object->firstName);
        $person->setLastName($object->lastName);
        return $person;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return Person::class === $to && DTOCreatePerson::class === $context['input']['class'];
    }

    public function initialize(string $inputClass, array $context = []): ?DTOCreatePerson
    {
        if (DTOCreatePerson::class !== $inputClass) {
            return null;
        }

        $person = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE];

        if (null === $person) {
            return null;
        }

        assert($person instanceof Person);

        $input = new $inputClass();
        assert($input instanceof DTOCreatePerson);

        $input->lastName = $person->getLastName();
        $input->firstName = $person->getFirstName();

        return $input;
    }
}
