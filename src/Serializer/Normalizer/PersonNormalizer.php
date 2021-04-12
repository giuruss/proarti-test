<?php


namespace App\Serializer\Normalizer;


use App\Entity\Donation;
use App\Entity\Person;
use App\Repository\DonationRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class PersonNormalizer implements NormalizerInterface, NormalizerAwareInterface
{

    use NormalizerAwareTrait;

    private const FORMATS = ['jsonld', 'json', 'xml'];
    private array $alreadyCalled = [];
    private DonationRepository $donationRepository;

    public function __construct(DonationRepository $donationRepository)
    {
        $this->donationRepository = $donationRepository;
    }

    /**
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    public function normalize($person, $format = null, array $context = []): iterable
    {
        assert($person instanceof Person);
        $this->alreadyCalled[] = $person->getId();
        $data = $this->normalizer->normalize($person, $format, $context);
        unset($this->alreadyCalled[\array_search($person->getId(), $this->alreadyCalled, true)]);
        $data['personTotalDonationAmount'] = $this->donationRepository->getDonationsTotalAmountPerPerson($person);

        return $data;
    }

    public function supportsNormalization($data, $format = null): bool
    {
        if (!$data instanceof Person || !\in_array($format, self::FORMATS, true)) {
            return false;
        }
        if (\in_array($data->getId(), $this->alreadyCalled, true)) {
            return false;
        }

        return true;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
