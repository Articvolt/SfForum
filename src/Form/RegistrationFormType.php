<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class)
        ->add('pseudonyme', TextType::class)
        ->add('agreeTerms', CheckboxType::class, [
            'mapped' => false,
            'constraints' => [
                new IsTrue([
                    'message' => "Vous devez accepter les conditions d'utilisations",
                ]),
            ],
        ])
        ->add('plainPassword', RepeatedType::class, [
            // le champ ne sera pas stocké en BDD
            'mapped' => false,
            'type' => PasswordType::class,
            'invalid_message' => 'Les mots de passes ne sont pas identiques.',
            'options' => ['attr' => ['class' => 'password field']],
            'required' => true, 
            'constraints' => [
                new Length([
                    'min'=> 8,
                    'minMessage' => 'Votre mot de passe nécessite {{ limit }} charactères',
                    'max' => 4096,
                    'maxMessage' => 'Your first name cannot be longer than {{ limit }} characters'
                ])
            ],
            'first_options' => ['label' => 'Mot de passe'],
            'second_options' => ['label' => 'Répéter le mot de passe'],            
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}


// https://symfony.com/doc/current/reference/forms/types/password.html