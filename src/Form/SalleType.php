<?php

namespace App\Form;

use App\Entity\Salle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Doctrine\ORM\EntityManagerInterface;

class SalleType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('adresse', Type\TextType::class, [
                'constraints' => [
                    new Constraints\NotBlank([
                        'message' => 'L\'adresse de la salle ne peut pas être vide.',
                    ]),
                    new Constraints\Callback([$this, 'validateAdresseUnique']),
                ],
            ])
            ->add('region')
            ->add('options', Type\ChoiceType::class, [
                'label' => 'Options',
                'required' => false,
                'multiple' => true,
                'choices' => [
                    'Wifi' => 'wifi',
                    'Parking' => 'parking',
                    'Nutritionniste' => 'nutritionniste',
                    'Climatisation' => 'climatisation',
                ],
            ])
            ->add('imageSalle', Type\FileType::class, [
                'label' => 'Logo :',
                'required' => false,
                'constraints' => [
                    new Constraints\File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image au format JPEG ou PNG.',
                    ]),
                ],
                'data_class' => null,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Salle::class,
            'translation_domain' => 'validators',
        ]);
    }

    public function validateAdresseUnique($value, ExecutionContextInterface $context)
    {
        $existingSalle = $this->entityManager->getRepository(Salle::class)->findOneBy(['adresse' => $value]);
        if ($existingSalle) {
            $context->buildViolation('Cette adresse de salle est déjà utilisée.')
                ->atPath('adresse')
                ->addViolation();
        }
    }
}
