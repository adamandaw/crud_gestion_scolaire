<?php

namespace App\Form;

use App\Entity\Etudiant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ResearchMatriculeEtudiantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('matricule', TextType::class, [
            'label' => 'Matricule',
            'attr' => [
                'placeholder' => "Taper le matricule de l'étudiant "
            ],
            'required' => false, // Le champ n'est pas obligatoire
            'constraints'=>[
                new NotBlank(['message'=>'Ce champ est obligatoire.'])
            ],
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Rechercher',
        ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
