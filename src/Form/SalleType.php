<?php

namespace App\Form;

use App\Entity\Salle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;





class SalleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('adresse')
            ->add('region')
            ->add('options', ChoiceType::class, [
                'label' => 'Options',
                'required' => false,
                'multiple' => true,
                'choices' => [
                    'Wifi' => 'wifi',
                    'Parking' => 'parking',
                    'Nutritionniste' => 'nutritionniste',
                    'Climatisions' => 'climatisions',
                ],
            ])
            ->add('imagesalle')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Salle::class,
        ]);
    }
}
