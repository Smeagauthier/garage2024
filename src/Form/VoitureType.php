<?php

namespace App\Form;

use App\Entity\Image;
use App\Entity\Voiture;
use PharIo\Manifest\Application;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\File;

class VoitureType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('marque', TextType::class, $this->getConfiguration("Marque", "Entrez la marque de la voiture"))
        ->add('modele', TextType::class, $this->getConfiguration("Modèle", "Entrez le modèle de la voiture"))
        ->add('coverImage', FileType::class, [
            'label' => "Image de couverture (fichier JPG ou PNG)",
            'required' => false,
            'mapped' => true, 
            'data_class' => null, // Pour ne pas avoir le formulaire de l'entité Image
            ])
        ->add('km', IntegerType::class, $this->getConfiguration("Kilométrage", "Entrez le kilométrage"))
        ->add('prix', MoneyType::class, [
            'currency' => false, // Désactiver le préfixe de la devise (plus joli)
            ]) 
        ->add('nbProprietaire', TextType::class, $this->getConfiguration("Nombre de propriétaires", "Entrez le nombre de propriétaires"))
        ->add('cylindree', IntegerType::class, $this->getConfiguration("Cylindrée", "Indiquez la cylindrée"))
        ->add('puissance', IntegerType::class, $this->getConfiguration("Puissance", "Entrez la puissance"))
        ->add('carburant', ChoiceType::class, [
            'choices' => [
                'Essence' => 'Essence',
                'Diesel' => 'Diesel',
                'Électrique' => 'Électrique',
                'Hybride' => 'Hybride',
            ],
            'placeholder' => 'Sélectionnez un type de carburant',
            'label' => 'Carburant',
            ])
            ->add('annee', IntegerType::class, $this->getConfiguration("Année", "Entrez l'année de fabrication"))
            ->add('transmission', ChoiceType::class, [
                'choices' => [
                    'Manuelle' => 'Manuelle',
                    'Automatique' => 'Automatique',
                    'Semi-Automatique' => 'Semi-automatique',
                ],
                'placeholder' => 'Sélectionnez une transmission',
                'label' => 'Transmission',
                ])
                ->add('description', TextareaType::class, $this->getConfiguration("Description", "Donnez une description"))
                ->add('autresOptions', TextareaType::class, $this->getConfiguration("Autres options", "Ajoutez des options supplémentaires"))
                ->add('images',CollectionType::class,[
                    'entry_type' => ImageType::class,
                    'allow_add' => true, // permet d'ajouter des éléments et surtout avoir data_prototype
                    'allow_delete' => true
                    ])
                    ;
                }
                
                public function configureOptions(OptionsResolver $resolver): void
                {
                    $resolver->setDefaults([
                        'data_class' => Voiture::class,
                    ]);
                }
            }