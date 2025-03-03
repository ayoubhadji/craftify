<?php

namespace App\Form;

use App\Entity\AventurierExpedition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AventurierExpeditionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           

            // Add a text field for status (you can use a choice type here too)
            ->add('status', TextType::class, [
                'label' => 'Status',
                'required' => true,
            ])

            // Add a date field for dateValidation (optional)
            ->add('dateValidation', DateTimeType::class, [
                'label' => 'Date Validation',
                'required' => false,
                'widget' => 'single_text',
                'attr' => ['class' => 'datetimepicker'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AventurierExpedition::class,
        ]);
    }
}
