<?php

namespace App\Form;

use App\Entity\Classe;
use App\Entity\ReInscription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ReInscriptionType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
       
        $builder
            // ->add('createAt')
            // ->add('classe')
            // ->add('classe',EntityType::class,[
            //     'class'=>Classe::class,
            //     "multiple"=>false,
            //     'choice_label'=>'libelle',
            //     "expanded"=>false,
            //     'label'  => 'Classe',
            // ])
            // ->add('classe',ChoiceType::class,[
            //     // 'class'=>Classe::class,
            //     // "multiple"=>false,
            //     'choice_label'=>'libelle',
            //     'choices' => $choices,
            //     // "expanded"=>false,
            //     'label'  => ' Test Classe',
            // ])
            ->add('montant', NumberType::class, [
                'constraints'=>[
                    new NotBlank(['message'=>'Ce champ est obligatoire.'])
                ],
                'required' => false, 
                'label'  => 'Montant',
                
            ])
            ->add('save',SubmitType::class,[
                "label"=>"Enregistrer",
                "attr"=>[
                    "class"=>"btn btn-primary float-right",
                    
                ]
            ])
            // ->add('isArchived')
            // ->add('etudiant')
            
            // ->add('anneeScolaire')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReInscription::class,
            'classeSuperieur' => [],
        ]);
    }
}
