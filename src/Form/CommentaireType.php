<?php

namespace App\Form;

use App\Entity\Commentaire;
use App\Entity\Post;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('contenu', TextareaType::class, [
                'label' => 'Contenu du commentaire',
                'attr' => ['placeholder' => 'Écrivez votre commentaire ici...'],
            ])
            ->add('date_commentaire', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date du commentaire',
            ])
            ->add('nmb_like', IntegerType::class, [
                'label' => 'Nombre de likes',
                'attr' => ['min' => 0],
            ])
            ->add('id_post', EntityType::class, [
                'class' => Post::class,
                'choice_label' => 'titre', // Assuming 'titre' is the title of the post
                'label' => 'Post associé',
            ])
            ->add('id_user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nom', // Assuming 'username' is the username of the user
                'label' => 'Utilisateur',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
