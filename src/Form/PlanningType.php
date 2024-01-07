<?php

namespace App\Form;

use App\Entity\Cours;
use App\Entity\Planning;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PlanningType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('createAt',DateType::class, [
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'label'  => 'Date du cours',
                'required'  => false,
                'constraints'=>[
                    new NotBlank(['message'=>'Ce champ est obligatoire.'])
                ],

            ])
            ->add('beginAt', TimeType::class, [
                'input'  => 'datetime_immutable',
                'widget' => 'choice',
                'label' => 'Heure de début',
                 'required'  => false,
                  'constraints'=>[
                    new NotBlank(['message'=>'Ce champ est obligatoire.'])
                ],
            ])
            ->add('endAt', TimeType::class, [
                'input'  => 'datetime_immutable',
                'widget' => 'choice',
                'label' => 'Heure de fin',
                 'required'  => false,
                  'constraints'=>[
                    new NotBlank(['message'=>'Ce champ est obligatoire.'])
                ],
            ])
            ->add('save',SubmitType::class,[
                "label"=>"Publier",
                "attr"=>[
                    "class"=>"btn  btn-sm float-right publier",
                ]
            ])
            // ->add('isArchived')
            // ->add('state')
            // ->add('cours',EntityType::class,[
            //     'class'=>Cours::class,
            //     "multiple"=>true,
            //     'choice_label'=>'id',
            //     "expanded"=>false,
            //     'label'  => 'cours',
            //     "required"=>false,
            //     'placeholder'  => "Choisir un niveau",
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Planning::class,
        ]);
    }
}
