<?php

namespace App\Form;

use App\Entity\AnneeScolaire;
use App\Repository\ClasseRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FilterByYearsType extends AbstractType
{
    private ClasseRepository $classeRepository;
    public function __construct(ClasseRepository $classeRepository)
    {
        $this->classeRepository=$classeRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('libelle',EntityType::class,[
            'choice_label'=>'libelle',
            'class'=>AnneeScolaire::class,
            "multiple"=>false,
            "expanded"=>false,
            "label"=>false,
            //  'choices' => array_combine(array_map(fn($value) => $value['libelle'], $this->classeRepository->findDistinctClasseLibelle()), array_map(fn($value) => $value['libelle'], $this->classeRepository->findDistinctClasseLibelle()))
        ])
        ->add('save',SubmitType::class,[
            "label"=>"Enregistrer",
            "attr"=>[
                // "class"=>"btn  btn-sm float-right",

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
