<?php

namespace App\Form;

use App\Entity\Foire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FoireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'required' => true,
                'attr' => ['minlength' => 3, 'maxlength' => 255],
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
                'attr' => ['minlength' => 10],
            ])
            ->add('date_debut', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('date_fin', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('lieu', TextType::class, [
                'required' => true,
                'attr' => ['minlength' => 2, 'maxlength' => 255],
            ])
            ->add('capacite_max', IntegerType::class, [
                'required' => true,
            ])
            ->add('created_at', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('prix', MoneyType::class, [
                'currency' => 'EUR',
                'required' => true,
            ])
            ->add('rate', NumberType::class, [
                'required' => true,
                'scale' => 1,
                'attr' => ['min' => 0, 'max' => 5, 'step' => 0.1],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Foire::class,
        ]);
    }
}
