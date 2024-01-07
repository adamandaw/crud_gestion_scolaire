<?php

namespace App\Form;

use App\Entity\Cours;
use App\Entity\Classe;
use App\Entity\Module;
use App\Entity\Niveau;
use App\Entity\Semestre;
use App\Entity\Professeur;
use App\Repository\ClasseRepository;
use App\Repository\ModuleRepository;
use App\Repository\NiveauRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfonycasts\DynamicForms\DependentField;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfonycasts\DynamicForms\DynamicFormBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CoursType extends AbstractType
{
    private ClasseRepository $classeRepo;
    private ModuleRepository $moduleRepo;
    private NiveauRepository $niveauRepo;
    public function __construct(ClasseRepository $classeRepo,ModuleRepository $moduleRepo,NiveauRepository $niveauRepo)
    {
        $this->classeRepo=$classeRepo;
        $this->moduleRepo=$moduleRepo;
        $this->niveauRepo=$niveauRepo;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
    //     ->add('classes',ChoiceType::class,[
    //         // 'class'=>Classe::class,
    //         'choice_label'=>'libelle',
    //         "placeholder"=>"All",
    //         'choices'  => array_combine(array_map(fn($value) => $value['id'], $this->classeRepo->findDistinctClasseLibelle()), array_map(fn($value) => $value['libelle'], $this->classeRepo->findDistinctClasseLibelle())),
    //          "expanded"=>true,
    //          "multiple"=>true,
    //         //  "required"=>false,
    //         //  "attr"=>[
    //         //     "class"=>"d-flex justify-content-between flex-wrap"
    //         // ]
    
    // ])
        ->add('niveau',EntityType::class,[
                'class'=>Niveau::class,
                "expanded"=>true,
                'choices'=>$this->niveauRepo->findAll(),
                'choice_attr'=>function($choice,$key,$value){
                    return ['data-path' => '/cours/new?niveau='.$value];
                },
                    'choice_label'=>'libelle',
                    "placeholder"=>"All",
                    "query_builder"=> function(){
                        return $this->niveauRepo->createQueryBuilder('n')->orderBy('n.libelle','ASC');
                    },
            
                ])
                
            // ->add('classes',EntityType::class,[
            //     'class'=>Classe::class,
            //     "multiple"=>true,
            //     'choice_label'=>'libelle',
            //     "expanded"=>false,
            //     "required"=>false,
            //     'label'  => 'Classe',
            //     'placeholder'  => "Choisir un niveau",
            // ])
            
            ->add('classes', EntityType::class, [
                'class' => Classe::class,
                'multiple' => true,
                'choice_label' => 'libelle',
                'expanded' => false,
                'required' => false,
                'label' => 'Classe',
                'placeholder' => 'Choisir un niveau',
                'query_builder' => function (ClasseRepository $classeRepo) use ($builder) {
                    $niveau = $builder->getData()->getNiveau(); // Récupère la valeur du champ "niveau" à partir du formulaire
                    return $classeRepo->createQueryBuilder('c')
                        ->where('c.niveau = :niveau')
                        ->setParameter('niveau', $niveau)
                        ->orderBy('c.libelle', 'ASC');
                },
            ])
            ->add('nbrHeureGlobal')
          
            ->add('professeur',EntityType::class,[
                    'class'=>Professeur::class,
                    'choice_label'=>'nomComplet',
                    "placeholder"=>"Professeur (choisir une classe)",
                     "required"=>false,
            ])
            ->add('module',EntityType::class,[
                'class'=>Module::class,
                'choice_label'=>'libelle',
                "placeholder"=>"Module (choisir une classe)",
                //  "expanded"=>false,
                 "required"=>false,
     
            ])
            ->add('semestre',EntityType::class,[
                'class'=>Semestre::class,
                'choice_label'=>'libelle',
                "placeholder"=>"All",
                 "expanded"=>false,
                //  "required"=>false,
                
            ])
            
                // bouton
            ->add('publier',SubmitType::class,[
                "label"=>"Publier",
                "attr"=>[
                    "class"=>"btn  btn-sm float-right fond-btn"
                ]
            ])
            
        ;
        // $formModifier = function(FormInterface $form, Niveau $niveau 
        //        = null){
        //         $classes =(null === $niveau) ? [] :  $niveau->getClasses();
        //         $form->add('classes',EntityType::class,[
        //             'class' => Classe::class,
        //             'multiple' => true,
        //             'choice_label' => 'libelle',
        //             'choice' => $classes,
        //             'required' => false,
        //             'label' => 'Classe',
        //             'placeholder' => 'Choisir un niveau',
        //         ]);
        // };
        // $builder->get('niveau')->addEventListener(FormEvents::POST_SUBMIT,function(FormEvent $event)  use ($formModifier){
        //     $niveau = $event->getForm()->getData();
        //     $formModifier($event->getForm()->getParent(),$niveau);
        //  });
        $builder->addEventListener(FormEvents::PRE_SET_DATA,function(FormEvent $event)  use ($builder){
            // $niveau = $builder->getData()->getNiveau();
            $form = $event->getForm();
            $data=$event->getData();
            // $niveau = $event->getForm()->getParent();
            // $niveau = $event->getForm()->getData();
            $niveau = $data->getNiveau();
            $niveau = 381;
            switch ($niveau) {
                case 380:
                    $classes=$this->classeRepo->findBy([
                        "niveau" => $niveau,
                        "isArchived" => false //380
                    ]);
                    break;
                case 381:
                    $classes=$this->classeRepo->findBy([
                        "niveau" => 381,
                        "isArchived" => false //380
                    ]);
                    break;
                
                default:
                    # code...
                    break;
            }
            // dd($data); 
           
            // dd($data); 
            
            // dd($classes); 
            $form->add('classes',EntityType::class,[
                'class'=>Classe::class,
                "multiple"=>true,
                'choice_label'=>'libelle',
                "expanded"=>false,
                'label'  => 'Classe',
                'choices'  => $classes,
                "required"=>false,
                'disabled' =>$classes === [] ?? true,
                'placeholder'  => "Choisir un niveau",
            ]);
            
         });
        
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
        ]);
    }
}
  // ->add('nbrHeurePlanifier')
            // ->add('nbrHeureEffectuer')
                        // // // ->add('anneeScolaire')
