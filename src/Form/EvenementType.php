<?php

namespace App\Form;

use App\Entity\Evenement;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Clock\Clock;
use Symfony\Component\Clock\MockClock;
class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomev', TextType::class, [
                'label' => 'Nom de l\'événement'
            ])
            ->add('descrptionev', TextType::class, [
                'label' => 'Description'
            ])
            ->add('dateddebutev', DateType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(['message' => 'La date de début est requise.']),
                    new Callback([$this, 'validateDateRange']),
                ],
                // Add any date validation options here
            ])
            ->add('datedfinev', DateType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(['message' => 'La date de fin est requise.']),
                    new Callback([$this, 'validateDateRange']),
                ],
                // Add any date validation options here
            ])
            ->add('heureev_hours', ChoiceType::class, [
                'label' => 'Heure',
                'choices' => array_combine(range(0, 23), range(0, 23)),
            ])
            ->add('heureev_minutes', ChoiceType::class, [
                'label' => 'Minutes',
                'choices' => array_combine(range(0, 59), range(0, 59)),
            ])
            
            ->add('lieu', TextType::class, [
                'label' => 'Lieu'
            ])
            ->add('city', ChoiceType::class, [
                'label' => 'Ville',
                'choices' => [
                    'Tunis' => 'Tunis',
                    'Sfax' => 'Sfax',
                    'Sousse' => 'Sousse',
                    'Kairouan' => 'Kairouan',
                    'Bizerte' => 'Bizerte',
                    'Gabes' => 'Gabes',
                    'Ariana' => 'Ariana',
                    'Gafsa' => 'Gafsa',
                    'Monastir' => 'Monastir',
                    'Mahdia' => 'Mahdia',
                    'BenArous' => 'BenArous',
                    'Nabeul' => 'Nabeul',
                    'Kebili' => 'Kebili',
                    'Tataouine' => 'Tataouine',
                    'Tozeur' => 'Tozeur',
                    'Medenine' => 'Medenine',
                    'Jendouba' => 'Jendouba',
                    'Siliana' => 'Siliana',
                    'Beja' => 'Beja',
                    'Kef' => 'Kef',
                    'SidiBouzid' => 'SidiBouzid',
                    'Kasserine' => 'Kasserine',
                    'Manouba' => 'Manouba',
                    'zaghouan' => 'zaghouan',
                ]
            ])
            ->add('tele', TextType::class, [
                'label' => 'Numéro de téléphone'
            ])
            ->add('email', TextType::class, [
                'label' => 'Email'
            ])
            ->add('FB_link', TextType::class, [
                'label' => 'Lien Facebook'
            ])
            ->add('IG_link', TextType::class, [
                'label' => 'Lien Instagram'
            ])
            ->add('genreEvenement', ChoiceType::class, [
                'label' => 'Genre de l\'événement',
                'choices' => [
                    'Competition' => 'competition',
                    'Cahrite' => 'cahrite',
                    'Spectacle' => 'spectacle',
                ]
            ])
            ->add('typeEV', ChoiceType::class, [
                'label' => 'Type de l\'événement',
                'choices' => [
                    'PublicEvent' => 'PublicEvent',
                    'LimitedPlace' => 'limitedPlace',
                ]
            ])
            ->add('capacite', IntegerType::class, [
                'label' => 'Capacité'
                // You can add validation options here
            ])
            ->add('lat', TextType::class, [
                'label' => 'Latitude'
            ])
            ->add('lon', TextType::class, [
                'label' => 'Longitude'
            ])
            // You can add more fields as needed
            ->add('photo', FileType::class, [
                'label' => 'Photo',
                'mapped' => false,
                'required' => false,
                'attr' => ['enctype' => 'multipart/form-data'], // Add this line
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
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter l\'événement'
            ]);
    }
    // Callback function to validate date range
    public function validateDateRange($value, ExecutionContextInterface $context)
{
    $data = $context->getRoot()->getData();

    $datedDebut = $data->getDateddebutev();
    $datedFin = $data->getDatedfinev();

    // Get today's date
    $today = new DateTime();

    if ($datedDebut > $datedFin) {
        $context->buildViolation('La date de début doit être antérieure à la date de fin.')
            ->atPath('dateddebutev')
            ->addViolation();
    }

    if ($datedDebut < $today) {
        $context->buildViolation('La date de début ne peut pas être dans le passé.')
            ->atPath('dateddebutev')
            ->addViolation();
    }
}
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
