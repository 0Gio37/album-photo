<?php

namespace App\Form;

use App\Entity\SearchByTag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchByTagType extends AbstractType
{/*
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'bg-gray-800 rounded-lg text-lg text-gray-100 w-96 p-4'],
                'label'=> false,
                'required'=>false,
            ])
            ->add('prenom', TextType::class, [
                'attr' => ['class' => 'bg-gray-800 rounded-lg text-lg text-gray-100 w-96 p-4'],
                'label'=>false,
                'required'=>false,

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchByTag::class,
        ]);
    }*/
}
