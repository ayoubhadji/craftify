<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type_post', ChoiceType::class, [
                'choices' => [
                    'Image' => 'image',
                    'Vidéo' => 'vidéo',
                    'Texte' => 'texte',
                ],
                'required' => true,
                'placeholder' => 'Sélectionnez un type de post',
            ])
            ->add('contenu', TextareaType::class, [
                'required' => true,
                'attr' => [
                    'minlength' => 10,
                    'placeholder' => 'Entrez le contenu du post',
                ],
            ])
            ->add('media_url', UrlType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrez l’URL du média',
                ],
            ])
            ->add('date_publication', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('tranche_dage', ChoiceType::class, [
                'choices' => [
                    'Enfant' => 'enfant',
                    'Adolescent' => 'adolescent',
                    'Adulte' => 'adulte',
                ],
                'required' => true,
                'placeholder' => 'Sélectionnez une tranche d’âge',
            ])
            ->add('nmb_like', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'min' => 0,
                ],
            ])
            ->add('id_user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nom', // Affiche le nom d'utilisateur au lieu de l'ID
                'required' => true,
                'placeholder' => 'Sélectionnez un utilisateur',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
