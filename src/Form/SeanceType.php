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
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class SeanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomseance', Type\TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom de la séance ne peut pas être vide.',
                    ]),
                ],
            ])
            ->add('debut', TimeType::class, [
                'widget' => 'single_text',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'L\'heure de début est requise.',
                    ]),
                ],
            ])
            ->add('fin', TimeType::class, [
                'widget' => 'single_text',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'L\'heure de fin est requise.',
                    ]),
                    new Callback([$this, 'validateFinAfterDebut']),
                ],
            ])
            ->add('dates', DateType::class, [
                'required' => false,
                'label' => 'Date de début',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'floatingInput',
                ],
                'constraints' => [
                    new DateTime([
                        'message' => 'La date de début doit être au format valide.',
                    ]),
                ],
            ])
            ->add('salle', EntityType::class, [
                'class' => Salle::class,
                'choice_label' => 'nom', // Assuming 'nom' is the property to display in the select options
                'required' => true,
                'label' => 'Salle', // Customize the label as needed
                'placeholder' => 'Sélectionnez une salle', // Optional placeholder text
                // You can add more options such as 'multiple' => true if needed
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectionner une salle.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Seance::class,
            'translation_domain' => 'validators', // Ensure translations for validation messages are loaded from "validators" domain
        ]);
    }

    public function validateFinAfterDebut($value, ExecutionContextInterface $context)
    {
        $seance = $context->getObject();
        $debut = $seance->getDebut();
        if ($debut && $value && $value < $debut) {
            $context->buildViolation('L\'heure de fin doit être après l\'heure de début.')
                ->atPath('fin')
                ->addViolation();
        }
    }
}
