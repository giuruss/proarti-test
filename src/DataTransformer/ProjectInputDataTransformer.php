<?php

declare(strict_types=1);

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInitializerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\DTO\DTOCreateProject;
use App\Entity\Project;

final class ProjectInputDataTransformer implements DataTransformerInitializerInterface
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function initialize(string $inputClass, array $context = []): ?DTOCreateProject
    {
        if (DTOCreateProject::class !== $inputClass) {
            return null;
        }

        $project = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE];
        if (null === $project) {
            return null;
        }
        \assert($project instanceof Project);

        $input = new $inputClass();
        \assert($input instanceof DTOCreateProject);

        $input->projectName = $project->getName();
        $input->rewardId = $project->getRewards();

        return $input;
    }

    public function transform($object, string $to, array $context = []): Project
    {
        \assert($object instanceof DTOCreateProject);
        $this->validator->validate($object);

        $project = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE];
        if (null === $project) {
            return new Project($object->projectName);
        }

        \assert($project instanceof Project);

        $project->setName($object->projectName);
        $project->addReward($object->rewardId);

        return $project;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return Project::class === $to && DTOCreateProject::class === $context['input']['class'];
    }
}
