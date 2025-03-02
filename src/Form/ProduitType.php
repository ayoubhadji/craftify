<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'required' => true,
                'attr' => [
                    'minlength' => 3,
                    'maxlength' => 255,
                    'placeholder' => 'Nom du produit',
                ],
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'maxlength' => 1000,
                    'placeholder' => 'Description du produit (facultatif)',
                ],
            ])
            ->add('prix', MoneyType::class, [
                'currency' => 'EUR',
                'required' => true,
                'attr' => [
                    'min' => 0.01,
                ],
            ])
            ->add('stock', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'min' => 0,
                    'placeholder' => 'Quantité en stock',
                ],
            ])
            ->add('img_file', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'download_uri' => true,
                'image_uri' => true,
                'attr' => [
                    'placeholder' => 'Choisissez une image',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
