<?php

namespace App\Form;

use App\Entity\Classe;
use App\Entity\Module;
use App\Entity\Professeur;
use App\Controller\ProfesseurController;
use App\Repository\ClasseRepository;
use App\Repository\ProfesseurRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProfesseurType extends AbstractType {
    private ProfesseurRepository $professeurRepository;
    private ClasseRepository $classerepository;
      public function __construct(ProfesseurRepository $professeurRepository,ClasseRepository $classerepository)
      {
        $this->professeurRepository=$professeurRepository;
        $this->classerepository=$classerepository;
      }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nomComplet', TextType::class, [
            'attr' => [
                'placeholder' => 'ex : toto',
                "class"=>""
            ],
        ])
            ->add('email',EmailType::class,[
                'constraints'=>[
                            new NotBlank(['message'=>'email est obligatoire'])
                ],
                "attr"=>[
                    "placeholder"=>"ex : toto@gmail.com"
                ]
            ])
            ->add('classes',EntityType::class,[
                'class'=>Classe::class,
                "multiple"=>true,
                'choice_label'=>'libelle',
                "expanded"=>false,
                "query_builder"=> function(){
                    return $this->classerepository->createQueryBuilder('c')
                    ->orderBy('c.libelle','ASC')
                    ->andWhere('c.isArchived = false');
                },
            ]) // A REVOIR JE DOIS AFFICHER LES CL NON ARCHIVER DANS TOUT LES FORMTYPES
            // ->add('classes',ChoiceType::class,[
            //     'choices' =>  $this->classerepository->findBy(["isArchived"=>false])
                
            // ])
            
            ->add('grade',ChoiceType::class, [
                'choices' => array_combine(array_map(fn($value) => $value['grade'], $this->professeurRepository->findDistinctGrade()), array_map(fn($value) => $value['grade'], $this->professeurRepository->findDistinctGrade()))
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


            // bouton
            ->add('save',SubmitType::class,[
                "label"=>"Enregistrer",
                "attr"=>[
                    "class"=>"btn  btn-sm float-right"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Professeur::class,
            'constraints' => new UniqueEntity(
                [ 
                    'fields'=> ['email'],
                    'message'=> 'email est unique',
                ]
            ),
        ]);
    }
}
