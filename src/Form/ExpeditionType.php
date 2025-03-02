<?php

namespace App\Form;

use App\Entity\Aventurier;
use App\Entity\Expedition;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class ExpeditionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_expedition', TextType::class, [
                'label' => 'Nom de l’expédition',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => 3,
                    'maxlength' => 255,
                    'placeholder' => 'Entrez le nom de l’expédition...',
                ],
                'help' => 'Le nom doit contenir au moins 3 caractères.',
            ])
            ->add('univers', TextType::class, [
                'label' => 'Univers',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'maxlength' => 100,
                    'placeholder' => 'Ex: Fantasy, Médiéval...',
                ],
            ])
            ->add('cart_tresor_url', UrlType::class, [ 
                'label' => 'Carte au trésor (URL)',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'https://example.com/map',
                ],
                'help' => 'Ajoutez un lien vers la carte du trésor.',
            ])
            ->add('quetes_dispo', TextareaType::class, [
                'label' => 'Quêtes disponibles',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                    'placeholder' => 'Décrivez les quêtes disponibles...',
                ],
            ])
            ->add('objet_magique', TextareaType::class, [
                'label' => 'Objet magique',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 3,
                    'placeholder' => 'Décrivez un objet magique de l’expédition...',
                ],
            ])
            ->add('gardien_artisanaux', TextareaType::class, [ 
                'label' => 'Gardiens artisanaux',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 3,
                    'placeholder' => 'Décrivez les gardiens artisanaux...',
                ],
            ])
            ->add('duree_mystique', TextType::class, [
                'label' => 'Durée mystique',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'maxlength' => 50,
                    'placeholder' => 'Ex: 3 jours, 1 mois...',
                ],
            ])
            ->add('secret_cache', TextareaType::class, [
                'label' => 'Secret caché',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 3,
                    'placeholder' => 'Décrivez un secret caché dans l’expédition...',
                ],
            ])
            ->add('relique_finale', TextType::class, [
                'label' => 'Relique finale',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'maxlength' => 255,
                    'placeholder' => 'Entrez le nom de la relique finale...',
                ],
            ])
            ->add('aventuriers', EntityType::class, [
                'class' => Aventurier::class,
                'label' => 'Aventuriers participants',
                'choice_label' => 'nom_code',
                'multiple' => true,
                'expanded' => false, 
                'attr' => [
                    'class' => 'form-select', 
                ],
                'help' => 'Sélectionnez les aventuriers pour cette expédition.',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Expedition::class,
        ]);
    }
}
