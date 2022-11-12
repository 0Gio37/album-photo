<?php

namespace App\Form;

use App\Entity\Album;
use App\Entity\Comment;
use App\Entity\Photo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PhotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentYear = date('Y');

        $builder
            ->add('album', EntityType::class, [
                'class'=>Album::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.titre', 'ASC');},
                'choice_label'=>'titre',
                'label'=>false,
                'attr' => ['class' => 'bg-gray-800 rounded-lg text-lg text-gray-100 flex justify-center m-auto lg:px-16 px-4 cursor-pointer w-4/5 py-2 lg:text-lg text-sm'],
            ])
            ->add('annee', IntegerType::class,[
                'required'=> false,
                'label'=> false, 'attr' => ['class' => 'bg-gray-800 rounded-lg text-white text-center m-auto lg:px-4 px-1 w-4/5 py-2 lg:text-lg text-sm'],
    ])
            ->add('lieu', TextType::class,[
                'required'=> false,
                'label'=> false, 'attr' => ['class' => 'bg-gray-800 rounded-lg text-white text-center m-auto g:px-4 px-1 w-4/5 py-2 lg:text-lg text-sm'],
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
