<?php

namespace App\Form;

use App\Entity\Aventurier;
use App\Entity\AventurierQuiz;
use App\Entity\Quiz;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AventurierQuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('score')
            ->add('datePassage', null, [
                'widget' => 'single_text',
            ])
            ->add('statut')
            ->add('certificatDelivre')
            ->add('aventurier', EntityType::class, [
                'class' => Aventurier::class,
                'choice_label' => 'id',
            ])
            ->add('quiz', EntityType::class, [
                'class' => Quiz::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AventurierQuiz::class,
        ]);
    }
}
