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
                'required' => true,
                'attr' => [
                    'minlength' => 3,
                    'maxlength' => 255,
                ],
            ])
            ->add('univers', TextType::class, [
                'required' => true,
                'attr' => [
                    'maxlength' => 100,
                ],
            ])
            ->add('cart_tresor_url', UrlType::class, [
                'required' => true,
            ])
            ->add('quetes_dispo', TextareaType::class, [
                'required' => true,
            ])
            ->add('objet_magique', TextareaType::class, [
                'required' => true,
            ])
            ->add('gardien_artisanaux', TextareaType::class, [
                'required' => true,
            ])
            ->add('duree_mystique', TextType::class, [
                'required' => true,
                'attr' => [
                    'maxlength' => 50,
                ],
            ])
            ->add('secret_cache', TextareaType::class, [
                'required' => true,
            ])
            ->add('relique_final', TextType::class, [
                'required' => true,
                'attr' => [
                    'maxlength' => 255,
                ],
            ])
            ->add('aventuriers', EntityType::class, [
                'class' => Aventurier::class,
                'choice_label' => 'nom', // Afficher le nom de l'aventurier au lieu de l'ID
                'multiple' => true,
                'expanded' => true, // Permet d'afficher sous forme de cases à cocher
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Expedition::class,
        ]);
    }
}
