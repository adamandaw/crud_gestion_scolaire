<?php

namespace App\Form;

use App\Entity\Classe;
use App\Repository\ClasseRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ResearchInscritsClasseType extends AbstractType
{
    private ClasseRepository $classeRepo;

    public function __construct(ClasseRepository $classeRepo)
    {
        $this->classeRepo=$classeRepo;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('classes',EntityType::class,[
            'class'=>Classe::class,
            // "multiple"=>false,
            'choice_label'=>'libelle',
            'label'=>'Rechercher par classe',
            // "expanded"=>false,
            "query_builder"=> function(){
                return $this->classeRepo->createQueryBuilder('c')
                ->orderBy('c.libelle','ASC')
                ->andWhere('c.isArchived = false');
            },
        ])
         // bouton
         ->add('save',SubmitType::class,[
            "label"=>"Enregistrer",
            "attr"=>[
                // "class"=>"btn  btn-sm float-right"   
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
