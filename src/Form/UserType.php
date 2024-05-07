<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

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
                'attr' => ['id' => 'user_mdp'],])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'MEMBRE' => 'MEMBRE',
                    'PROPRIETAIRE' => 'PROPRIETAIRE',
                ],
                'placeholder' => 'Choisir votre role', // Optional placeholder text
            ]) ->add('image', FileType::class, [
                'label' => 'image',
                'mapped' => true,
                'required' => false,
                'attr' => ['enctype' => 'multipart/form-data' , 'class'=>'form-control form-control-sm bg-dark' ,'id'=>'formFileSm','onchange'=>'displaySelectedImage(this)'], // Add this line
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'password_required' => true,
            'disable_password_field' => false,
            
        ]);
    }
    
}