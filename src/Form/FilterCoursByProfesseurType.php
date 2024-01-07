<?php

namespace App\Form;

use App\Entity\Professeur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FilterCoursByProfesseurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
             ->add('professeur',EntityType::class,[
                    'class'=>Professeur::class,
                    'choice_label'=>'nomComplet',
                    "placeholder"=>"(veuillez choisir un prof)",
                     "required"=>false,
                     "label"=>false,
                     "expanded"=>true,
                     'constraints'=>[
                                new NotBlank(['message'=>'Choix obligatoire.'])
                            ]
            ])
            ->add('save',SubmitType::class,[
                "label"=>"appliquer le filtre",
                "attr"=>[
                    "class"=>"float-right submit",
                    // "data-toggle"=>"modal",
                    // "data-target"=>"#modalFiltre",
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
