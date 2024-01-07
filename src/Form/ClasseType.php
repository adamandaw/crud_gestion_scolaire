<?php

namespace App\Form;

use App\Entity\Classe;
use App\Entity\Niveau;
use App\Entity\Filiere;
use App\Entity\Module;
use App\Entity\Professeur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class ClasseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('libelle',TextType::class,[
            //     'required'=>false,
            //     'constraints'=>[
            //         new NotBlank(['message'=>'Le libelle est obligatoire'])
            //     ]
            // ])
          

            ->add('filiere',EntityType::class,[
                'class'=>Filiere::class,
                'choice_label'=>'libelle'
            ])
            
            ->add('niveau',EntityType::class,[
                'class'=>Niveau::class,
                'choice_label'=>'libelle',
                "placeholder"=>"All",
                 "expanded"=>true,
                //  "required"=>false,
                 "attr"=>[
                    "class"=>"d-flex justify-content-between flex-wrap"
                ]
                 
                
            ])
            ->add('professeurs',EntityType::class,[
                'class'=>Professeur::class,
                "multiple"=>true,
                'choice_label'=>'nomComplet',
                "expanded"=>false,
            ])
            ->add('modules',EntityType::class,[
                'class'=>Module::class,
                'choice_label'=>'libelle',
                 "multiple"=>true,
                 "expanded"=>false,
                "attr"=>[
                    "class"=>"d-flex justify-content-around flex-wrap"
                ]
            ])

            
            ->add('isArchived')

            ->add('save',SubmitType::class,[
                "label"=>"Enregistrer",
                "attr"=>[
                    "class"=>"btn  btn-sm float-right",
                ]
            ])
            

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Classe::class,
            'constraints'=>[
                new UniqueEntity([
                    'fields' => ['libelle'],
                    "message"=>"Le nom de la classe est unique"
                ])
            ]
        
        ]);
    }
}
