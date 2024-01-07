<?php

namespace App\Form;

use App\Repository\ProfesseurRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ResearchProfesseurGradeType extends AbstractType
{
    private ProfesseurRepository $professeurRepository;
      public function __construct(ProfesseurRepository $professeurRepository)
      {
        $this->professeurRepository=$professeurRepository;
      }
      
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('grade',ChoiceType::class, [
            'choices' => array_combine(array_map(fn($value) => $value['grade'], $this->professeurRepository->findDistinctGrade()), array_map(fn($value) => $value['grade'], $this->professeurRepository->findDistinctGrade())),
            "placeholder"=>"Selectionner un grade",
            // 'choices'  => array_map(fn($value): array => [$value['grade']=>$value['grade']] ,$this->professeurRepository->findDistinctGrade()) 
             ])

             ->add('save',SubmitType::class,[
                "label"=>"Appliquer",
                "attr"=>[
                    "class"=>"btn btn",

                    

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
