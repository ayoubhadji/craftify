<?php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'required' => true,
                'attr' => [
                    'minlength' => 3,
                    'maxlength' => 255,
                ],
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
                'attr' => ['maxlength' => 255],
            ])
            ->add('capacite', NumberType::class, [
                'required' => true,
                'attr' => ['min' => 1],
            ])
            ->add('type_evenement', TextType::class, [
                'required' => true,
                'attr' => ['maxlength' => 255],
            ])
            ->add('prix', NumberType::class, [
                'required' => true,
                'attr' => ['min' => 0],
            ])
            ->add('organisateur', TextType::class, [
                'required' => true,
                'attr' => ['maxlength' => 255],
            ])
            ->add('image', UrlType::class, [
                'required' => true,
            ])
            ->add('date_creation', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
