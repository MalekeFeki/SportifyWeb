<?php

namespace App\Form;

use App\Entity\Paiement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType; // Import PasswordType
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Intl\Countries;

class PaiementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('country', ChoiceType::class, [
                'choices' => array_flip(Countries::getNames()),
                'placeholder' => 'Choose a country',
            ])
            ->add('ncb', PasswordType::class, [ // Use PasswordType for ncb
                'label' => 'NCB', // Set custom label
            ])
            ->add('cvc', PasswordType::class, [ // Use PasswordType for cvc
                'label' => 'CVC', // Set custom label
            ])
            ->add('expdate')
            ->add('postalcode', PasswordType::class, [ // Use PasswordType for postalcode
                'label' => 'Postal Code', // Set custom label
            ])
            ->add('promocode')
            ->add('price')
            ->add('pdate');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Paiement::class,
        ]);
    }
}
