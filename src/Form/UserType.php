<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use VictorPrdh\RecaptchaBundle\Form\ReCaptchaType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'required'=> true,
                'label'=> ' ',
                'attr' => [
                    'placeholder' => 'Nom ',
                    'class' => 'form-control block w-full p-4 text-lg rounded-sm bg-black']
            ])
            ->add('prenom', TextType::class, [
                'required'=> true,
                'label'=> ' ',
                'attr' => [
                    'placeholder' => 'Prenom ',
                    'class' => 'form-control block w-full p-4 text-lg rounded-sm bg-black']
            ])
            ->add('username', TextType::class, [
                'invalid_message'=>'Ce pseudonyme existe dejà !',
                'required'=> true,
                'label'=> ' ',
                'attr' => [
                    'placeholder' => 'Pseudo ',
                    'class' => 'form-control block w-full p-4 text-lg rounded-sm bg-black']
            ])
            ->add('mail', EmailType::class, [
                'invalid_message'=>'Cette adresse mail  existe déjà !',
                'required'=> true,
                'label'=> ' ',
                'attr' => [
                    'placeholder' => 'Adresse mail ',
                    'class' => 'form-control block w-full p-4 text-lg rounded-sm bg-black']
            ])
            ->add('password', PasswordType::class, [
                'required'        => true,
                'label'=> ' ',
                'attr' => [
                    'placeholder' => 'Mot de passe ',
                    'class' => 'form-control block w-full p-4 text-lg rounded-sm bg-black']
            ])
            ->add('captcha', ReCaptchaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
