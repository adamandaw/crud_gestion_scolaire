<?php

namespace App\Form;

use App\Entity\Classe;
use App\Entity\Niveau;
use App\Repository\ProfesseurRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FilterCoursByClasseType extends AbstractType
{
    private Security $security;
    private ProfesseurRepository $professeurRepository;
    public function __construct(Security $security,ProfesseurRepository $professeurRepository)
    {
        $this->security= $security;
        $this->professeurRepository= $professeurRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();
        if ($this->security->isGranted('ROLE_PROFESSEUR')) {
        // $id = $user->getId();
        $currentProfesseur = $this->professeurRepository->find($user);
            $classesOfProfesseur=$currentProfesseur->getClasses()->toArray();
            // dd($classesOfProfesseur);
        $builder
            ->add('classe',ChoiceType::class,[
                // 'class'=>Classe::class,
                'choice_label'=>'libelle',
                'choices'=>$classesOfProfesseur,
                "placeholder"=>"Filtrer par une (de vos classes)",
                 "required"=>false,
                 "label"=>false,
                 "expanded"=>false,
                 'constraints'=>[
                            new NotBlank(['message'=>'Choix obligatoire.'])
                        ]
                 ])
        ->add('save',SubmitType::class,[
            "label"=>"rechercher",
            "attr"=>[
                "class"=>"float-right submit fond",
                // "data-toggle"=>"modal",
                // "data-target"=>"#modalFiltre",
            ]
        ])
        ;
            }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
