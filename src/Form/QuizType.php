<?php

namespace App\Form;

use App\Entity\Quiz;
use App\Entity\Expedition; //
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class QuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dureeMax')
            ->add('expedition', EntityType::class, [
                'class' => Expedition::class,
                'choice_label' => 'titre', // Or any other field of the Expedition entity
                'placeholder' => 'Choose an expedition',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
        ]);
    }
}
