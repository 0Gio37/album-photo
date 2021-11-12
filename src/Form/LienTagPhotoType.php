<?php

namespace App\Form;

use App\Entity\LienTagPhoto;
use App\Entity\Photo;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LienTagPhotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tag', EntityType::class,[
                'class'=> Tag::class
            ])
            ->add('photo',EntityType::class,[
                'class'=> Photo::class
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LienTagPhoto::class,
        ]);
    }
}
