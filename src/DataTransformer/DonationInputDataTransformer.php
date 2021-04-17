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
        $input->personId = $donation->getPerson();
        $input->rewardId = $donation->getReward();

        return $input;
    }

    public function transform($object, string $to, array $context = []): Donation
    {
        assert($object instanceof DTOCreateDonation);
        $this->validator->validate($object);

        $donation = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE];
        if (null === $donation) {
            return new Donation($object->amount, $object->personId, $object->rewardId);
        }

        assert($donation instanceof Donation);

        $donation->setAmount($object->amount);
        $donation->setPerson($object->personId);
        $donation->setReward($object->rewardId);
        return $donation;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return Donation::class === $to && DTOCreateDonation::class === $context['input']['class'];
    }
}
