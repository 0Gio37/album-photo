<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\Theme;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlbumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('titre',TextType::class, [
                'attr' => ['class' => 'bg-gray-800 rounded-lg  text-gray-100 flex justify-start m-auto w-96 p-4 lg:text-lg text-sm focused'],
                'label'=>false
    ])
            ->add('theme', EntityType::class, [
                'class'=>Theme::class,
                'choice_label'=>'titre',
                'attr' => ['class' => 'bg-gray-800 rounded-lg  text-gray-100 flex justify-start m-auto w-96 p-4  lg:text-lg text-sm'],
                'label'=>false
            ])
            ->add('annee', IntegerType::class,  [
                'attr' => ['class' => 'bg-gray-800 rounded-lg  text-gray-100 flex justify-start m-auto w-96 p-4 2 lg:text-lg text-sm'],
                'required' => false,
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
