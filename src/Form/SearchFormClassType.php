<?php

namespace App\Form;

use App\Entity\Niveau;
use App\Entity\Filiere;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchFormClassType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('niveau',EntityType::class,[
            "class"=>Niveau::class,
            "placeholder"=>"Selectionner un niveau",
            "required"=>false,
            'choice_label'=>'libelle'
          
        ])
        ->add('filiere',EntityType::class,[
            "class"=>Filiere::class,
             "placeholder"=>"Selectionner une filiere",
             "required"=>false,
             'choice_label'=>'libelle'
        ])
        ->add('save',SubmitType::class,[
            "label"=>"Appliquer",
            "attr"=>[
                "class"=>"btn btn btn-sm"
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
