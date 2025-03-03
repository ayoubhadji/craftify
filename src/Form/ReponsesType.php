<?php

namespace App\Form;

use App\Entity\Reponses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Ajout de la question (texte) en tant que champ de texte
        $builder
            ->add('label', TextType::class, [
                'label' => 'Réponse',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrez votre réponse',
                ],
            ])
            // Champ de choix pour la validité de la réponse (par exemple : correcte ou non)
            ->add('isCorrect', ChoiceType::class, [
                'label' => 'Est-ce la bonne réponse ?',
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'expanded' => true,  // Affiche les options sous forme de boutons radio
                'multiple' => false, // Ne permet de choisir qu'une seule option
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reponses::class,  // Associe ce formulaire à l'entité Reponses
        ]);
    }
}
