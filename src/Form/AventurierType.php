<?php

namespace App\Form;

use App\Entity\Aventurier;
use App\Entity\Expedition;
use App\Repository\ExpeditionRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType; 
use Symfony\Component\Form\Extension\Core\Type\DateType;

class AventurierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'form-control'],
                'required' => true,
                'help' => 'Entrez le nom de l\'aventurier.',
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['class' => 'form-control'],
                'required' => true,
                'help' => 'Entrez le prénom de l\'aventurier.',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['class' => 'form-control'],
                'required' => true,
                'help' => 'Entrez l\'email de l\'aventurier.',
            ])
            ->add('dateInscription', DateType::class, [
                'required' => false, // Le champ peut être vide
                'widget' => 'single_text', // Affiche un champ de saisie de date
                'empty_data' => null, // Si aucun choix n'est fait, `null` est envoyé
            ])
            
            ->add('statut', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'Actif' => 'actif',
                    'Inactif' => 'inactif',
                    'En pause' => 'en pause',
                ],
                'attr' => ['class' => 'form-control'],
                'required' => true,
                'help' => 'Sélectionnez le statut de l\'aventurier.',
            ])
           
            ->add('phoneNumber', TextType::class, [
                'required' => false,
                'label' => 'Numéro de téléphone',
                'data' => '+216', // Le préfixe +216 est ajouté par défaut
                'attr' => [
                    'placeholder' => 'Entrez le numéro sans le préfixe',
                ],
            ])
            ->add('expeditions', EntityType::class, [
                'class' => Expedition::class,
                'choice_label' => 'titre',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Expéditions',
                'attr' => ['class' => 'form-control'],
                'required' => false,
                'query_builder' => function (ExpeditionRepository $repository) {
                    return $repository->createQueryBuilder('e')
                        ->orderBy('e.titre', 'ASC');
                },
                'help' => 'Sélectionnez les expéditions auxquelles cet aventurier a participé.',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Aventurier::class,
        ]);
    }
}
