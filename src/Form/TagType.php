<?php

namespace App\Form;

use App\Entity\Tag;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'required'=>false,
                'label'=>false,
                'attr' => ['class' => 'bg-gray-800 rounded-lg text-xl text-gray-100 flex justify-start m-auto px-6 w-1/3 py-2'],
            ])
            ->add('prenom', TextType::class, [
                'required'=>true,
                'label'=>false,
                'attr' => ['class' => 'bg-gray-800 rounded-lg text-xl text-gray-100 flex justify-start m-auto px-6 w-1/3 py-2'],
            ])
            ->add('lieu', TextType::class, [
                'required'=>true,
                'label'=>false,
                'attr' => ['class' => 'bg-gray-800 rounded-lg text-xl text-gray-100 flex justify-start m-auto px-6 w-1/3 py-2'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tag::class,
        ]);
    }

}
