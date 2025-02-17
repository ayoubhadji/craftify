<?php

namespace App\Form;

use App\Entity\Art;
use App\Entity\Foire;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Titre de l\'art'],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control', 'rows' => 4, 'placeholder' => 'Décrivez l\'art'],
            ])
            ->add('image', UrlType::class, [
                'label' => 'URL de l\'image',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Lien de l\'image'],
            ])
            ->add('foire', EntityType::class, [
                'class' => Foire::class,
                'choice_label' => 'nom', // Assurez-vous que "nom" est un champ valide de l'entité Foire
                'label' => 'Foire associée',
                'placeholder' => 'Sélectionnez une foire',
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Art::class,
        ]);
    }
}
