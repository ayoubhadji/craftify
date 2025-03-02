<?php
namespace App\Form;

use App\Entity\SliderItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Foire;

class SliderItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile', FileType::class, [
                'label' => 'Image (JPG or PNG file)',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid JPG or PNG image',
                    ])
                ],
            ])
            ->add('altText', TextType::class, [
                'label' => 'Alt Text'
            ])
            ->add('url', TextType::class, [
                'label' => 'URL'
            ])
            ->add('position', IntegerType::class, [
                'label' => 'Position'
            ])

            ->add('foire', EntityType::class, [
                'class' => Foire::class,           // The entity class to display
                'choice_label' => 'nom',           // The field to show for each option
                'placeholder' => 'Choisir une foire',  // Optional placeholder for the select field
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SliderItem::class,
        ]);
    }
}
