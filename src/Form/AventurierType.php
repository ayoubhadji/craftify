<?php

namespace App\Form;

use App\Entity\Aventurier;
use App\Entity\Expedition;
use App\Repository\ExpeditionRepository;  // ✅ Corrected import
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AventurierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom_code', TextType::class, [
            'label' => 'Nom de code',
            'attr' => ['class' => 'form-control'],
            'required' => true,
        ])
        
            ->add('quetes_terminees', TextType::class, [
                'label' => 'Quêtes terminées',
                'attr' => ['class' => 'form-control'],
                'required' => true,
                'help' => 'Indiquez le nombre de quêtes terminées.',
            ])
            ->add('artefact_possede', TextType::class, [
                'label' => 'Artefact possédé',
                'attr' => ['class' => 'form-control'],
                'required' => true,
                'help' => 'Entrez le nom de l\'artefact possédé.',
            ])
            ->add('compagne_creatif', TextType::class, [
                'label' => 'Compagnon créatif',
                'attr' => ['class' => 'form-control'],
                'required' => true,
                'help' => 'Entrez le nom du compagnon créatif.',
            ])
            ->add('badge_legendaire', TextType::class, [
                'label' => 'Badge légendaire',
                'attr' => ['class' => 'form-control'],
                'required' => true,
                'help' => 'Indiquez le badge légendaire que possède l\'aventurier.',
            ])
            ->add('signe_distinctif', TextType::class, [
                'label' => 'Signe distinctif',
                'attr' => ['class' => 'form-control'],
                'required' => true,
                'help' => 'Décrivez un signe distinctif de cet aventurier.',
            ])
            // ✅ Corrected field name to match entity relationship
            ->add('expeditions', EntityType::class, [
                'class' => Expedition::class,
                'choice_label' => 'nomExpedition',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Expéditions',
                'attr' => ['class' => 'form-control'],
                'required' => false,
                'query_builder' => function (ExpeditionRepository $repository) {
                    return $repository->createQueryBuilder('e')
                        ->orderBy('e.nomExpedition', 'ASC');
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
