<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id_user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nom',  // Use "nom" or another valid field
                'label' => 'User',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotNull(['message' => 'L’utilisateur est obligatoire.']),
                ],
            ])
            ->add('type_post', ChoiceType::class, [
                'choices' => [
                    'Image' => 'image',
                    'Vidéo' => 'vidéo',
                    'Texte' => 'texte',
                ],
                'placeholder' => 'Sélectionnez un type de post',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le type de post est obligatoire.']),
                ],
            ])
            ->add('contenu', TextareaType::class, [
                'attr' => ['class' => 'form-control', 'rows' => 4, 'minlength' => 10],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le contenu est obligatoire.']),
                    new Assert\Length([
                        'min' => 10,
                        'minMessage' => 'Le contenu doit contenir au moins 10 caractères.',
                    ]),
                ],
            ])
            ->add('mediaFile', FileType::class, [
                'label' => 'Upload Image',
                'required' => false,
                'mapped' => false,
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\File([
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif'],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPG, PNG, GIF).',
                        'maxSize' => '5M',
                    ]),
                ],
            ])
            ->add('tranche_dage', ChoiceType::class, [
                'choices' => [
                    'Enfant' => 'enfant',
                    'Adolescent' => 'adolescent',
                    'Adulte' => 'adulte',
                ],
                'placeholder' => 'Sélectionnez une tranche d’âge',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La tranche d’âge est obligatoire.']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
