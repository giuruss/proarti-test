<?php

namespace App\Form;

use App\DTO\DTOupload;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UploadType extends AbstractType
{
    final public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', FileType::class, [
                'label' => 'Choisir le fichier',
                'mapped' => false,
                'required' => false,

                'constraints' => [
                    new File([
                        'maxSize' => '5000K',
                        'mimeTypes' => [
//                            'application/csv',
                        ],
                        'mimeTypesMessage' => 'Prière de sélectionner un ficher csv valide',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    final public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DTOupload::class,
        ]);
    }
}
