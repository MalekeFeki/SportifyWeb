<?php

namespace App\Form;

use App\Entity\Eventreservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Evenement;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use VictorPrdh\RecaptchaBundle\Form\ReCaptchaType;

class EventreservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'floatingInput'
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'floatingInput'
                ]
            ])
            ->add('cin', IntegerType::class, [
                'label' => 'CIN',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'floatingInput'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'floatingInput'
                ],
                'label_attr' => [
                    'class' => 'custom-label',

                ]
            ])
            ->add('numTele', IntegerType::class, [
                'label' => 'Numéro de téléphone',
                'required' => false,

            ])

            ->add('eventid', EntityType::class, [
                'class' => Evenement::class,
                'label' => 'Événement',
                'choice_label' => 'nomev', // Assuming 'nomev' is the property representing the name of the event
                'required' => false,
            ])->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'homepage',
                
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Eventreservation::class,
        ]);
    }
}
