<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Form\SearchFormClassType;
use App\Repository\UserRepository;
use App\Repository\CoursRepository;
use App\Repository\ClasseRepository;
use App\Repository\NiveauRepository;
use App\Form\FilterCoursByClasseType;
use App\Form\FilterCoursByModuleType;
use App\Repository\PlanningRepository;
use App\Repository\ProfesseurRepository;
use App\Form\ResearchProfesseurGradeType;
use App\Repository\InscriptionRepository;
use App\Repository\AnneeScolaireRepository;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private ProfesseurRepository $professeurRepository;
      public function __construct(ProfesseurRepository $professeurRepository)
      {
        $this->professeurRepository=$professeurRepository;
      }

    #[Route('/home', name: 'app_home',methods:["GET","POST"])]
    #[IsGranted('ROLE_AC', statusCode: 423)]
    public function index(ClasseRepository $classeRepository, Request $request,PaginatorInterface $paginator,ProfesseurRepository $professeurRepository): Response
    {        
        
        
        // listes de classe
        $classes=$classeRepository->findBy(['isArchived'=>false]);
        $pagination = $paginator->paginate($classes,$request->query->get('page',1),3);

        // filtre de classe
        $form=$this->createForm(SearchFormClassType::class);
        $form->handleRequest($request);
        $filtre=[   
           
            'isArchived'=>false
        ];
        $classeFiltrer=[];
        if ($form->isSubmitted()) {
            if( $form->get("niveau")->getData()!=null) {
                $filtre["niveau"]=$form->get("niveau")->getData();
            }
            if( $form->get("filiere")->getData()!=null) {
                $filtre["filiere"]=$form->get("filiere")->getData();
            }
        }
        $classeFiltrer=$classeRepository->findBy($filtre);
        // $classeFiltrer=$paginator->paginate($cl,$request->query->get('page',1),3);
 
        //listes de professeur
        $profs=$professeurRepository->findAll();
        $paginationProfs=$paginator->paginate($profs,$request->query->get('page',1),3);
        //  dd($classeFiltrer);
        // filtre grade de professeur
        $professeurFiltrer=[];
        $grade='';
        $formGrade=$this->createForm(ResearchProfesseurGradeType::class);
        $formGrade->handleRequest($request);
        if ($formGrade->isSubmitted() && $formGrade->isValid()) {
        }
        $grade = $formGrade->get('grade')->getData();
         $professeurFiltrer = $this->professeurRepository->findBy(['grade' => $grade]);
        //  dd($professeurFiltrer);
        return $this->render('home/index.html.twig',[
            'pagination'=>$pagination,
            'paginationProfs'=>$paginationProfs,
            "form"=> $form->createView(),
            "classeFiltrer"=> $classeFiltrer,
            "formGrade"=> $formGrade->createView(),
            "grade"=> $grade,
            "professeurFiltrer"=> $professeurFiltrer,
        ]);
    }
    #[Route('u/home', name: 'app_home_other_users',methods:["GET","POST"])]
    // #[IsGranted(['ROLE_PROFESSEUR','ROLE_ETUDIANT'])]
    // #[IsGranted('ROLE_ETUDIANT', statusCode: 423)]
    /**
 * @IsGranted({"ROLE_PROFESSEUR", "ROLE_ETUDIANT"})
 */
    public function indexPageOthesUsers(ClasseRepository $classeRepository, 
    PaginatorInterface $paginator,SessionInterface $session,
    ProfesseurRepository $professeurRepository,UserRepository $userRepository,
    Security $security, CoursRepository $coursRepository,
    AnneeScolaireRepository $yearsRepository,NiveauRepository $niveauRepository,
    PlanningRepository $planningRepository,Request $request,
    InscriptionRepository $inscriptionRepository,): Response
    {    
        $formModulesSelect = null;    
        $currentYear = $yearsRepository->findOneBy(["isActive"=>true]);
        $currentPlanning=[];
        $filterClassePlanning=[];
        $pl=[];
        
// ------------------------------------PROFESSEUR FILTRE PAR DATE 
        if ($request->isMethod('POST')  && $request->request->has('findByDate')) { 
                extract($_POST);
               
                $toDateTime = new DateTimeImmutable($date);
                // dd($toDateTime);
                $data = $planningRepository->findBy([
                    "createAt"=>$toDateTime,
                    "state"=>false,
                    "isArchived"=>false
                ]);
                // foreach ($data as $key => $value) {
                //     $dateFormater = $value->getCreateAt();
                    
                //     $formater= $dateFormater->format('d/m/Y');
                //     // dd($formater);  
                //     if ($formater == $date) {
                //         // dd($date);  
                //         $currentPlanning[]=$data[$key];
                //         // dd($currentPlanning);
                //     }else {
                //         // dd('dara');
                //     }
                // }
                $filterClassePlanning[]=($data);

        }
          // Filtre du professeur SES CLASSES
          $formClasseSelect=$this->createForm(FilterCoursByClasseType::class);
          $formClasseSelect->handleRequest($request);
          // dd($formClasseSelect->get("niveau")->getData());
        if ($formClasseSelect->isSubmitted() && $formClasseSelect->isValid()) {
            $data=($formClasseSelect->getData());
            $coursesOfClasse=$data['classe']->getCours()->toArray();
            $CoursesId= array_map(function($id){
                    return $id->getid();
            },$coursesOfClasse);
            //  dd($data);
            $planingByClasse=$planningRepository->findBy([
                "cours"=>$CoursesId,
                "state"=>false,
                "isArchived"=>false
            ]);
            $filterClassePlanning[]=$planingByClasse;
            // dd($planingByClasse);
        }
        $user = $security->getUser();
        if ($user instanceof \App\Entity\Professeur) {
            $id = $user->getId();
            $cours = $coursRepository->findBy([
                 "professeur"=> $id,
                "isDone"=> false,
                "anneeScolaire"=> $currentYear->getId(),
            ]);
            //  dd($cours);
            if (!empty($cours)) {
                foreach ($cours as   $value) {
                    $courId = $value->getId();
                //recherche planiing via id du cours
                $dataPlanning=$planningRepository->findBy([
                    "cours"=>$courId,
                    "state"=>false,
                    "isArchived"=>false
                ]);
                $currentPlanning[]=$dataPlanning;
            }
                // dd($currentPlanning);
            }
            // dd($currentProfesseur->getClasses()->toArray());
           
           
           
        //    ----------------------------------------------------- // ETUDIANT
        } elseif ($user instanceof \App\Entity\Etudiant) {
              // Filtre  SES MODULE
              if ($this->isGranted('ROLE_ETUDIANT')) {
                    $formModulesSelect=$this->createForm(FilterCoursByModuleType::class);
                    $formModulesSelect->handleRequest($request);
                    if ($formModulesSelect->isSubmitted() && $formModulesSelect->isValid()) {
                        $data=($formModulesSelect->getData());
                        $coursByModule=$data['modules']->getCours()->toArray();
                        // $etudiantCourId= array_map(function($id){
                        //     if ($id->isIsDone() == false) {
                        //         return $id->getid();
                        //     }
                        // },$coursByModule);
                        
                        foreach ($coursByModule as $key => $value) {
                            if ($value->isIsDone() == false) {
                                $plannings = $value->getPlannings()->toArray();
                                // dd($plannings);
                                foreach ($plannings as  $planningEtu) {
                                    if ($planningEtu->isIsArchived() == false && $planningEtu->isState() == false) {
                                        // dd("RO");
                                        $pl[]=$planningEtu;
                                        // return ($pl);
                                    }
                                }
                                // return $id->getid();
                            }
                        }
                        //  dd($coursByModule);
                        //  dd($data['modules']->getCours()->toArray());
                    }
                }
            // dd($formModulesSelect->get("niveau")->getData());
            $email = $user->getEmail();
            $etudiant=$inscriptionRepository->findOneBy([
                'anneeScolaire' => $currentYear,
                'etudiant' => $user->getId(),
                'isArchived' => false,
                ]);
                $classeId =$etudiant->getClasse()->getId();
                $currentClasseEtudiant=$classeRepository->findOneBy(['id' => $classeId,
                'isArchived' => false]);
                $tabCours = $currentClasseEtudiant->getCours()->toArray();
                $CourId= array_map(function($id){
                    return $id->getid();
                },$tabCours);
            //  dd($tabCours);
                $planningEtudiant=$planningRepository->findBy([
                    "cours"=>$CourId,
                    "state"=>false,
                    "isArchived"=>false
                ]);
            //  dd($planningEtudiant);

                $currentPlanning[]=($planningEtudiant);
                // dd($currentPlanning);
        }
        // dd($etudiant);
       

        // dd($pl);
        // foreach ($libelles as $key => $value) {
        //     dd(count($value));
        // }
        return $this->render('home/index.othersUser.html.twig',[
            "currentPlanning"=> $currentPlanning,
            // "classesLibelle"=> implode(',',$libelles),
            // PROFDEESSER FILTRE
            "formClasseSelect"=> $formClasseSelect->createView(),
            "filterClassePlanning"=> $filterClassePlanning,
            // Etudiant FILTRE
            // "formModulesSelect"=> $formModulesSelect->createView(),
            "formModulesSelect" => $formModulesSelect ? $formModulesSelect->createView() : null,
            "pl" => $pl,
        ]);
    }

    
}
        // 'pagination'=>$pagination,
            // 'paginationProfs'=>$paginationProfs,
            // "form"=> $form->createView(),
            // "classeFiltrer"=> $classeFiltrer,
            // "formGrade"=> $formGrade->createView(),
// // listes de classe
        // $classes=$classeRepository->findBy(['isArchived'=>false]);
        // $pagination = $paginator->paginate($classes,$request->query->get('page',1),3);

        // // filtre de classe
        // $form=$this->createForm(SearchFormClassType::class);
        // $form->handleRequest($request);
        // $filtre=[   
           
        //     'isArchived'=>false
        // ];
        // $classeFiltrer=[];
        // if ($form->isSubmitted()) {
        //     if( $form->get("niveau")->getData()!=null) {
        //         $filtre["niveau"]=$form->get("niveau")->getData();
        //     }
        //     if( $form->get("filiere")->getData()!=null) {
        //         $filtre["filiere"]=$form->get("filiere")->getData();
        //     }
        // }
        // $classeFiltrer=$classeRepository->findBy($filtre);
        // // $classeFiltrer=$paginator->paginate($cl,$request->query->get('page',1),3);
 
        // //listes de professeur
        // $profs=$professeurRepository->findAll();
        // $paginationProfs=$paginator->paginate($profs,$request->query->get('page',1),3);
        
        // // filtre grade de professeur
        // $professeurFiltrer=[];
        // $grade='';
        // $formGrade=$this->createForm(ResearchProfesseurGradeType::class);
        // $formGrade->handleRequest($request);
        // if ($formGrade->isSubmitted() && $formGrade->isValid()) {
        // }
        // $grade = $formGrade->get('grade')->getData();
        //  $professeurFiltrer = $this->professeurRepository->findBy(['grade' => $grade]);
        //  dd($professeurFiltrer);