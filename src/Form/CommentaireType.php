<?php

namespace App\Form;

use App\Entity\Commentaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('contenu', TextareaType::class, [
            'label' => 'Your Comment',
            'attr' => ['class' => 'form-control required-comment', 'rows' => 3], // ✅ Added minlength attribute
            'constraints' => [
                new Assert\NotBlank(['message' => 'Le commentaire ne peut pas être vide.']), // ✅ Required field
                new Assert\Length([
                    'min' => 3,
                    'minMessage' => 'Le commentaire doit contenir au moins 3 caractères.', // ✅ Minimum length of 3 characters
                    'max' => 500,
                    'maxMessage' => 'Le commentaire ne peut pas dépasser 500 caractères.', // ✅ Prevent spam
                ]),
            ],
        ]);
        
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
