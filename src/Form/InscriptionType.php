<?php

namespace App\Form;

use App\Entity\Classe;
use App\Form\EtudiantType;
use App\Entity\Inscription;
use App\Repository\ClasseRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class InscriptionType extends AbstractType
{
    private ClasseRepository $classeRepository;
    public function __construct(ClasseRepository $classeRepository)
    {
        $this->classeRepository=$classeRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('createAt')
            // ->add('isArchived')
            ->add('etudiant',EtudiantType::class,[
                "label"=>false,
                ]) 

            // ->add('classe',ChoiceType::class,[
            //     "multiple"=>false,
            //     "expanded"=>false,
            //     'choices' => array_combine(array_map(fn($value) => $value['libelle'], $this->classeRepository->findDistinctClasseLibelle()), array_map(fn($value) => $value['libelle'], $this->classeRepository->findDistinctClasseLibelle()))

            // ])
            ->add('classe',EntityType::class,[
                'class'=>Classe::class,
               
                'choice_label'=>'libelle',
                "expanded"=>false,
                "query_builder"=> function(){
                    return $this->classeRepository->createQueryBuilder('c')
                    ->orderBy('c.libelle','ASC')
                    ->andWhere('c.isArchived = false');
                },
                
            ])
            ->add('save',SubmitType::class,[
                "label"=>"Enregistrer",
                "attr"=>[
                    // "class"=>"btn  btn-sm float-right",
                ]
            ])
            // ->add('anneeScolaire')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Inscription::class,
        ]);
    }
}
