<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\Comment;
use App\Entity\Photo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PhotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentYear = date('Y');

        $builder
            ->add('file', FileType::class,[
                'label'=>'Charger une photo',
                'mapped' => false,
                'multiple'=>false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
    ])
            ->add('album', EntityType::class, [
                'class'=>Album::class,
                'choice_label'=>'titre',
            ])
            ->add('year',
                IntegerType::class,[
                'data'=>$currentYear,
                'label'=>'Année',
        ])
            ->add('commentaire', TextareaType::class,[
                'required'=>false
    ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Photo::class,
        ]);
    }
}
