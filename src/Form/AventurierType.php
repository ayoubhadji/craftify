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

class AventurierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_code', TextType::class, [
                'label' => 'Nom de code',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le nom de code',
                ],
            ])
            ->add('quetes_terminees', TextareaType::class, [
                'label' => 'Quêtes terminées',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Listez les quêtes terminées',
                ],
            ])
            ->add('artefact_possede', TextType::class, [
                'label' => 'Artefact possédé',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez l’artefact possédé',
                ],
            ])
            ->add('compagne_creatif', TextType::class, [
                'label' => 'Compagnon créatif',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le compagnon créatif',
                ],
            ])
            ->add('badge_legendaire', TextType::class, [
                'label' => 'Badge légendaire',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le badge légendaire',
                ],
            ])
            ->add('signe_distinctif', TextareaType::class, [
                'label' => 'Signe distinctif',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Décrivez le signe distinctif',
                ],
            ])
            ->add('id_expedition', EntityType::class, [
                'class' => Expedition::class,
                'choice_label' => 'id',
                'multiple' => true,
                'label' => 'Expéditions',
                'attr' => [
                    'class' => 'form-select',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Aventurier::class,
        ]);
    }
}
