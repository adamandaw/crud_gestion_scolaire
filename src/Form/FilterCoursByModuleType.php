<?php

namespace App\Form;

use App\Repository\ClasseRepository;
use Symfony\Component\Form\AbstractType;
use App\Repository\InscriptionRepository;
use App\Repository\AnneeScolaireRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FilterCoursByModuleType extends AbstractType
{
    private Security $security;
    private InscriptionRepository $inscriptionRepository;
    private AnneeScolaireRepository $yearsRepository;
    private ClasseRepository $classeRepository;
    public function __construct(Security $security,InscriptionRepository $inscriptionRepository,
    AnneeScolaireRepository $yearsRepository,ClasseRepository $classeRepository)
    {
        $this->security= $security;
        $this->inscriptionRepository= $inscriptionRepository;
        $this->yearsRepository= $yearsRepository;
        $this->classeRepository= $classeRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();
        $currentYear=$this->yearsRepository->findOneBy(['isActive' => true,]);
        $etudiant=$this->inscriptionRepository->findOneBy([
            'anneeScolaire' => $currentYear,
            'etudiant' => $user,
            'isArchived' => false,
            ]);

            $classe =$this->classeRepository->findOneBy([
                'id' => $etudiant->getClasse()->getId(),
                ]);
            $modules =($classe->getModules()->toArray());
            // dd($modules);

        if ($this->security->isGranted('ROLE_ETUDIANT')) {
        $builder
            ->add('modules',ChoiceType::class,[
                // 'class'=>Classe::class,
                'choice_label'=>'libelle',
                'choices'=>$modules,
                "placeholder"=>"Filtrer par (module)",
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
