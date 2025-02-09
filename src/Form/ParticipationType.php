<?php

namespace App\Form;

use App\Entity\Evenement;
use App\Entity\Participation;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prix', MoneyType::class, [
                'currency' => 'EUR',
                'required' => true,
                'attr' => [
                    'min' => 0,
                ],
            ])
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'En attente' => 'en attente',
                    'Confirmé' => 'confirmé',
                    'Annulé' => 'annulé',
                ],
                'required' => true,
                'placeholder' => 'Choisissez un statut',
            ])
            ->add('id_user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username', // Affiche le nom d'utilisateur au lieu de l'ID
                'required' => true,
                'placeholder' => 'Sélectionnez un utilisateur',
            ])
            ->add('id_evenement', EntityType::class, [
                'class' => Evenement::class,
                'choice_label' => 'titre', // Affiche le titre de l'événement au lieu de l'ID
                'required' => true,
                'placeholder' => 'Sélectionnez un événement',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participation::class,
        ]);
    }
}
