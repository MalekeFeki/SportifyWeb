<?php

namespace App\Form;

use App\Entity\Evenement;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomev', TextType::class, [
                'required' => false,
                'label' => 'Nom de l\'événement',
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'floatingInput',
                    'style' => 'margin-bottom: 1rem !important;'
                ]
            ])
            ->add('descrptionev', TextType::class, [
                'required' => false,
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'floatingInput'
                ]
            ])
            ->add('dateddebutev', DateType::class, [
                'required' => false,
                'label' => 'Date de début',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(['message' => 'La date de début est requise.']),
                    new Callback([$this, 'validateDateRange']),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'floatingInput'
                ]
                // Add any date validation options here
            ])
            ->add('datedfinev', DateType::class, [
                'required' => false,
                'label' => 'Date de fin',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(['message' => 'La date de fin est requise.']),
                    new Callback([$this, 'validateDateRange']),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'floatingInput'
                ]
                // Add any date validation options here
            ])
            ->add('heureev_hours', ChoiceType::class, [
                'required' => false,
                'label' => 'Heure',
                'choices' => array_combine(range(0, 23), range(0, 23)),
                'constraints' => [
                    new NotBlank(['message' => 'L\'heure est requise.']),
                    // Add any other validation constraints here
                ],
                'attr' => [
                    'class' => 'form-select',
                    'id' => 'floatingSelect',
                    'aria-label'=> 'Floating label select example'
                    
                ],
            ])
            ->add('heureev_minutes', ChoiceType::class, [
                'required' => false,
                'label' => 'Minutes',
                'choices' => array_combine(range(0, 59), range(0, 59)),
                'attr' => [
                    'class' => 'form-select',
                    'id' => 'floatingSelect',
                    'aria-label'=> 'Floating label select example'
                    
                ],
            ])

            ->add('lieu', TextType::class, [
                'required' => false,
                'label' => 'Lieu',
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'floatingInput'
                ]
            ])
            ->add('city', ChoiceType::class, [
                'required' => false,
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
                ],
                'attr' => [
                    'class' => 'form-select',
                    'id' => 'floatingSelect',
                    'aria-label'=> 'Floating label select example'
                ],
            ])
            ->add('tele', TextType::class, [
                'required' => false,
                'label' => 'Numéro de téléphone',
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'floatingInput'
                ]
            ])
            ->add('email', TextType::class, [
                'required' => false,
                'label' => 'Email',
                'attr' => [
                   
                ]
            ])
            ->add('fb_link', TextType::class, [
                'required' => false,
                'label' => 'Lien Facebook',
                'constraints' => [
                    new NotBlank(['message' => 'Le lien Facebook ne peut pas être vide.']),
                ],
                // 'attr' => [
                //     'class' => 'form-control',
                //     'id' => 'floatingInput'
                // ]
            ])
            ->add('IG_link', TextType::class, [
                'required' => false,
                'label' => 'Lien Instagram',
                'constraints' => [
                    new NotBlank(['message' => 'Le lien Instagram ne peut pas être vide.']),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'floatingInput'
                ]
            ])
            ->add('genreEvenement', ChoiceType::class, [
                'required' => false,
                'label' => 'Genre de l\'événement',
                'choices' => [
                    'Competition' => 'competition',
                    'Cahrite' => 'cahrite',
                    'Spectacle' => 'spectacle',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le genre de l\'événement ne peut pas être vide.']),
                ],
                'attr' => [
                    'class' => 'form-select',
                    'id' => 'floatingSelect',
                    'aria-label'=> 'Floating label select example'
                ]
            ])
            ->add('typeEV', ChoiceType::class, [
                'required' => false,
                'label' => 'Type de l\'événement',
                'choices' => [
                    'PublicEvent' => 'PublicEvent',
                    'LimitedPlace' => 'limitedPlace',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le type de l\'événement ne peut pas être vide.']),
                ],
                'attr' => [
                    'class' => 'form-select',
                    'id' => 'floatingSelect',
                    'aria-label'=> 'Floating label select example'
                ]
            ])
            ->add('capacite', IntegerType::class, [
                'required' => false,
                'label' => 'Capacité',
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'floatingInput'
                ]
                // You can add validation options here
            ])
            // Add hidden fields for latitude and longitude
            ->add('lat', HiddenType::class)
            ->add('lon', HiddenType::class)
            // You can add more fields as needed
            ->add('photo', FileType::class, [
                'label' => 'Photo',
                'mapped' => false,
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
            // ->add('submit', SubmitType::class, [
            //     'label' => 'Ajouter l\'événement'
            // ]);
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
