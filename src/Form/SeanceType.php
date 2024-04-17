<?php

namespace App\Form;

use App\Entity\Seance;
use App\Entity\Salle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SeanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomseance')
            ->add('debut', TimeType::class, [
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('fin', TimeType::class, [
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('dates', DateType::class, [
                'required' => false,
                'label' => 'Date de début',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'floatingInput'
                ]
            ])
            // Add a field to select the associated Salle entity
            ->add('salle', EntityType::class, [
                'class' => Salle::class,
                'choice_label' => 'nom', // Assuming 'nom' is the property to display in the select options
                'required' => true,
                'label' => 'Salle', // Customize the label as needed
                'placeholder' => 'Select a Salle', // Optional placeholder text
                // You can add more options such as 'multiple' => true if needed
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Seance::class,
        ]);
    }

}
