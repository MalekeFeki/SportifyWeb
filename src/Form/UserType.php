<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cin')
            ->add('num_tel')
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('mdp', PasswordType::class, [
                'label' => 'Mot de passe',
                'required' => true,
                'attr' => ['id' => 'user_mdp'],])
            ->add('showPassword', CheckboxType::class, [
                'label' => 'Afficher le mot de passe',
                'required' => false,
                'attr' => ['id' => 'user_showPassword'], // La case à cocher n'est pas obligatoire
            ])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'MEMBRE' => 'MEMBRE',
                    'PROPRIETAIRE' => 'PROPRIETAIRE',
                ],
                'placeholder' => 'Choisir votre role', // Optional placeholder text
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
    
}