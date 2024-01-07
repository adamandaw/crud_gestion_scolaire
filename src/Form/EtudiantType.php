<?php

namespace App\Form;

use App\Form\UserType;
use App\Entity\Etudiant;
use App\Repository\ClasseRepository;
use App\Repository\EtudiantRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EtudiantType extends UserType
{
    private ClasseRepository $classeRepository;
    private EtudiantRepository $etudiantRepository;
    public function __construct(ClasseRepository $classeRepository,EtudiantRepository $etudiantRepository)
    {
        $this->classeRepository=$classeRepository;
        $this->etudiantRepository=$etudiantRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder->remove('email'); // pour enlever un champs specifique du parent
        $builder
            // ->add('email')
            // ->add('roles')
            // ->add('password')
            // ->add('nomComplet')
            ->add('dateDeNaissanceAt',DateType::class, [
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'label'  => 'Date de naissance',
                'required'  => false,
                'constraints'=>[
                    new NotBlank(['message'=>'Ce champ est obligatoire.'])
                ],

            ])
            
            ->add('sexe', ChoiceType::class, [
                'choices'  => [
                    'M' => 'M',
                    'F' => 'F',
                ],
                'label'  => 'Genre',
                'expanded' => true,
                // 'required'  => false,
                'multiple' => false,
                'constraints'=>[
                            new NotBlank(['message'=>'Ce champ est obligatoire.'])
                        ],

            ])
            ->add('lieuDeNaissance', TextType::class, [
                // 'class'=>Etudiant::class,
                // 'choice_label'=>'lieuDeNaissance',
                // 'choices' => array_combine(array_map(fn($value) => $value['grade'], $this->professeurRepository->findDistinctGrade()), array_map(fn($value) => $value['grade'], $this->professeurRepository->findDistinctGrade())),
                // 'expanded' => false,
                'required' => false,
                'constraints'=>[
                            new NotBlank(['message'=>'Ce champ est obligatoire.'])
                        ],

            ])
            ->add('tuteur', TextType::class, [
                'required'  => false,
                'attr' => [
                    'placeholder' => 'ex : toto',
                    "class"=>""
                ]
                ,'constraints'=>[
                    new NotBlank(['message'=>'Ce champ est obligatoire.'])
                ],
            ])
            // ->add('matricule') 
            
            // ->add('user',null,[
            //     // "label"=>true,
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Etudiant::class,
            'constraints'=>[
                // new UniqueEntity([
                //     'fields' => ['email'],
                //     "message"=>"Ce champ doit être unique."
                // ]),
                // new NotBlank([
                //     'groups' => ['tuteur','dateDeNaissanceAt'],
                //     'message'=>'Ce champ est obligatoire.'
                // ])
            ]
        ]);
    }
}
