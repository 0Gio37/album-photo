<?php

namespace App\Form;

use App\Entity\SearchByYear;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchByYearType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('annee', TextType::class, [
                'attr' => ['class' => 'bg-gray-800 rounded-lg text-lg text-gray-100 w-96 p-4'],
                'label'=>false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchByYear::class,
        ]);
    }
}
