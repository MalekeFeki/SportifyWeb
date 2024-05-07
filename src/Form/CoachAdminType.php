<?php

namespace App\Form;

use App\Entity\CoachAdmin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType; // Ajout de l'import pour FileType
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoachAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('Nom')
            ->add('Prenom')
            ->add('Description')
            ->add('photo', FileType::class, [ // Ajoutez le champ photo de type FileType
                'label' => 'Photo (JPEG or PNG file)',
                'mapped' => false, // Ne pas mapper ce champ à une propriété de l'entité Coach
                'required' => false, // La photo n'est pas obligatoire
                'attr' => ['accept' => 'image/jpeg,image/png'], // Accepter uniquement les fichiers JPEG et PNG
            ])
            ->add('sexe', ChoiceType::class, [
                'choices' => [
                    'Homme' => 'HOMME',
                    'FEMME' => 'FEMME',
                ],
                'placeholder' => 'Choisir votre Sexe', // Optional placeholder text
                
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CoachAdmin::class,
        ]);
    }
}
