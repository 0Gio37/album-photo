<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\SearchByTheme;
use App\Entity\Theme;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchByThemeType extends AbstractType
{/*
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', EntityType::class, [
                'class'=>Theme::class,
                'choice_label'=>'titre',
                'label'=>false,
                'attr' => ['class' => 'bg-gray-800 rounded-lg text-lg text-gray-100 flex justify-center m-auto px-16 py-2'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchByTheme::class,
        ]);
    }*/
}
