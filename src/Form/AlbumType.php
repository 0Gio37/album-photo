<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\Theme;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlbumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentYear = date('Y');

        $builder
            ->add('titre',TextType::class, [
                'attr' => ['class' => 'w-96 rounded-lg'],
                'label'=>false
    ])
            ->add('theme', EntityType::class, [
                'class'=>Theme::class,
                'choice_label'=>'titre',
                'attr' => ['class' => 'w-96 rounded-lg'],
                'label'=>false
            ])
            ->add('annee', TextType::class,  [
                'data'=>$currentYear,
                'attr' => ['class' => 'w-96 rounded-lg'],
                'label'=>false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Album::class,
        ]);
    }
}
