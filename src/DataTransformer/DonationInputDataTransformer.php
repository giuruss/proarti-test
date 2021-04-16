<?php


namespace App\DataTransformer;


use ApiPlatform\Core\DataTransformer\DataTransformerInitializerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\DTO\DTOCreateDonation;
use App\Entity\Donation;

final class DonationInputDataTransformer implements DataTransformerInitializerInterface
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function initialize(string $inputClass, array $context = []): ?DTOCreateDonation
    {
        if (DTOCreateDonation::class !== $inputClass) {
            return null;
        }

        $donation = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE];
        if (null === $donation) {
            return null;
        }
        assert($donation instanceof Donation);

        $input = new $inputClass();
        assert($input instanceof DTOCreateDonation);

        $input->amount = $donation->getAmount();
        $input->person = $donation->getPerson();
        $input->reward = $donation->getReward();

        return $input;
    }

    public function transform($object, string $to, array $context = []): Donation
    {
        assert($object instanceof DTOCreateDonation);
        $this->validator->validate($object);

        $donation = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE];
        if (null === $donation) {
            return new Donation($object->amount, $object->person, $object->reward);
        }

        assert($donation instanceof Donation);

        $donation->setAmount($object->amount);
        $donation->setPerson($object->person);
        $donation->setReward($object->reward);
        return $donation;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return Donation::class === $to && DTOCreateDonation::class === $context['input']['class'];
    }
}
