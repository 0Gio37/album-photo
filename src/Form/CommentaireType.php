<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\Commentaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('texte', TextareaType::class, [
                'required'=>false,
                'label'=>false,
                'attr' => ['class' => 'bg-gray-800 rounded-md text-gray-100 flex justify-start m-auto h-56 px-4 w-full py-2 lg:text-lg text-sm'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
