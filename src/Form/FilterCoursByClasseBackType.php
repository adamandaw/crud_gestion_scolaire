<?php

namespace App\Form;

use App\Entity\Classe;
use App\Repository\ClasseRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FilterCoursByClasseBackType extends AbstractType
{
    private ClasseRepository $classeRepo;

    public function __construct(ClasseRepository $classeRepo)
    {
        $this->classeRepo=$classeRepo;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('classe',EntityType::class,[
            'class'=>Classe::class,
            'choice_label'=>'libelle',
            // 'choices'  => array_combine(array_map(fn($value) => $value['id'], $this->classeRepo->findDistinctClasseLibelle()), array_map(fn($value) => $value['libelle'], $this->classeRepo->findDistinctClasseLibelle())),
            "placeholder"=>"(veuillez choisir une classe)",
             "required"=>false,
            //  "label"=>false,
             "expanded"=>false,
             'constraints'=>[
                        new NotBlank(['message'=>'Choix obligatoire.'])
             ],
             "query_builder"=> function(){
                return $this->classeRepo->createQueryBuilder('c')
                ->orderBy('c.libelle','ASC')
                ->andWhere('c.isArchived = false');
            },
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
