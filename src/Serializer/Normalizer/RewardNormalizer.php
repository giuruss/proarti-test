<?php


namespace App\Serializer\Normalizer;


use App\Entity\Project;
use App\Entity\Reward;
use App\Repository\ProjectRepository;
use App\Repository\RewardRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class RewardNormalizer implements NormalizerInterface, NormalizerAwareInterface
{

    use NormalizerAwareTrait;

    private const FORMATS = ['jsonld', 'json', 'xml'];
    private array $alreadyCalled = [];
    private RewardRepository $rewardRepository;

    public function __construct(RewardRepository $rewardRepository)
    {
        $this->rewardRepository = $rewardRepository;
    }

    public function normalize($person, $format = null, array $context = []): iterable
    {
        assert($person instanceof Project);
        $this->alreadyCalled[] = $person->getId();
        $data = $this->normalizer->normalize($person, $format, $context);
        unset($this->alreadyCalled[\array_search($person->getId(), $this->alreadyCalled, true)]);
        $data['project'] = $this->rewardRepository->getTotalRewardsQuantityByProject($person);

        return $data;
    }

    public function supportsNormalization($data, $format = null): bool
    {
        if (!$data instanceof Project || !\in_array($format, self::FORMATS, true)) {
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
