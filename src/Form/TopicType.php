<?php

namespace App\Form;

use DateTime;
use App\Entity\Topic;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TopicType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nameTopic', TextType::class,
            ['label' => 'Nom du sujet', 'attr' => 
            ['class' => 'form-input']
            ])
            ->add('submit', SubmitType::class, ['label' => 'valider', 'attr' => [ 'class' => 'form-submit']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Topic::class,
        ]);
    }
}
