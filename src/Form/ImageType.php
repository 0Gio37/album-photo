<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fileName', FileType::class,[
                'label'=>false,
                'attr' => ['class' => 'text-blue-500 bg-gray-800 rounded-lg italic flex justify-center lg:text-base text-xs m-auto lg:px-28 px-4 lg:py-4 py-2 cursor-pointer'],
                'mapped' => false,
                'multiple'=>false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '2001k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'image/svg',
                        ],
                        'mimeTypesMessage' => 'Format invalide : .jpeg, .jpg, .png, .svg',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
