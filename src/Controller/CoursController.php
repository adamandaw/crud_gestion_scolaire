<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Form\CoursType;
use App\Entity\Planning;
use App\Form\PlanningType;
use App\Repository\CoursRepository;
use App\Repository\ClasseRepository;
use App\Repository\AbsenceRepository;
use App\Repository\PlanningRepository;
use App\Form\FilterCoursBySemestreType;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\FilterCoursByClasseBackType;
use App\Form\FilterCoursByProfesseurType;
use App\Repository\InscriptionRepository;
use App\Repository\AnneeScolaireRepository;
use Knp\Component\Pager\PaginatorInterface;
use PhpParser\Node\Stmt\Return_;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route('/cours')]
#[IsGranted('ROLE_AC', statusCode: 423)]
class CoursController extends AbstractController
{
    #[Route('/', name: 'app_cours_index', methods: ['GET', 'POST'])]
    public function index(CoursRepository $coursRepository,Request $request,
                        PlanningRepository $planningRepository,
                        AnneeScolaireRepository $yearsRepository,
                        PaginatorInterface $paginator): Response
    {

        $currentYear = $yearsRepository->findOneBy(["isActive"=>true]);
        // dd();
        $cours=$coursRepository->findBy([ "isDone"=> false,
                                            "anneeScolaire"=> $currentYear->getId()]);
        $argsCommun =[];
        
        // $currentPlanning=$planningRepository->findCountOfSessionHasTrue($coursIds); A REVOIR
        // dd($currentPlanning);
// ------------- FILTRE PROFESSEUR
        $formProfesseur=$this->createForm(FilterCoursByProfesseurType::class);
        $formProfesseur->handleRequest($request);
        $profId=0;
        $selfCourses=[];
        
        if ($formProfesseur->isSubmitted() && $formProfesseur->isValid()) {
            $data=($formProfesseur->getData());
            foreach ($data as  $value) {
                $profId= $value->getId();
                $selfCourses = $coursRepository->findBy([ "professeur"=> $profId,
                                                "isDone"=> false,
                                                "anneeScolaire"=> $currentYear->getId(),
                                            ]);
                $argsCommun[]=$selfCourses;
            }
        }
        //  dd($selfCourses);
        
        // dd($argsCommun);
// --------------------- FILTRE SEMESTRTE
        $formSemestre=$this->createForm(FilterCoursBySemestreType::class);
        $formSemestre->handleRequest($request);
        $semestreId=0;
        $selfSemestre=[];
        if ($formSemestre->isSubmitted() && $formSemestre->isValid()) {
            $dataSemestre=($formSemestre->getData());
            $selfSemestre = array_map(function($id){
                return $id->getId();
            },$dataSemestre);
            $semestres = $coursRepository->findBy([ "semestre"=> $selfSemestre,
            "isDone"=> false,
            "anneeScolaire"=> $currentYear->getId(),
            ]);
        // dd($semestres);
        $argsCommun[]=$semestres;
        }

// ------------------- FILTRE CLASSE
        $formClasse=$this->createForm(FilterCoursByClasseBackType::class);
        $formClasse->handleRequest($request);
        if ($formClasse->isSubmitted() && $formClasse->isValid()) {
            $dataClasse=($formClasse->getData());
           $cours= ($dataClasse['classe']->getCours()->toArray());
            foreach ($cours as $key => $value) {
                if ($value->isIsDone() == false) {
                    // dd($);
                    $argsCommun[]=$value;
                }
                
            }
        }
        // dd($dataClasse['classe']->getCours()->toArray());
        // dd($cours);
       
        return $this->render('cours/index.html.twig', [
            'cours' => $paginator->paginate($cours ,$request->query->get('page',1),3),
            "formProfesseur"=> $formProfesseur->createView(),
            "selfCourses"=> $selfCourses, // jenleve ce tableau ulterieurement
            "formSemestre"=> $formSemestre->createView(),
            "formClasse"=> $formClasse->createView(),
            // "semestres"=> $semestres, // ici aussi enleve
            "argsCommun"=> $argsCommun,
        ]);
    }

    #[Route('/new', name: 'app_cours_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_AC', statusCode: 423)]
    public function new(Request $request, EntityManagerInterface $entityManager,
    AnneeScolaireRepository $yearsRepository): Response
    {
        $cour = new Cours();
        $form = $this->createForm(CoursType::class, $cour);
        $form->handleRequest($request);
        // dd($cour);
        // $niveau =31;
        if($request->isXmlHttpRequest() || $request->query->get('niveau')!=0) {
            $niveau =(int) $request->query->get('niveau');
            dd($niveau);
        }
        $cour->setAnneeScolaire($yearsRepository->findOneBy([
            "isActive"=> true
          ]));
        //   dd($form->getData()); 
        if ($form->isSubmitted() && $form->isValid()) {
            
            $niveau = $form->get('niveau')->getData();
            // dd('test');
            //  dd($form->getData());
            // dd($cour);
            $entityManager->persist($cour);
            $entityManager->flush();

            return $this->redirectToRoute('app_cours_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cours/new.html.twig', [
            'cour' => $cour,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/nouveau{niveau?}', name: 'inscription_filtre_classe')] public function showInscriptionByClasse(
        InscriptionRepository $repoIns, ClasseRepository $repoClasse, SessionInterface $session, Request $request ): Response
           {
               if($request->isXmlHttpRequest() || $request->query->get('niveau')!=0) {
                   $niveau =(int) $request->query->get('niveau');
                   dd($niveau);
                   $classe = $repoClasse->find($niveau );
                   $anneeEncours = $session->get("anneeEncours");
                   
                  // $session->set("inscrits", $inscrits);
                   $session->set("classeSelected", $classe);
               }
               return new JsonResponse($this->generateUrl('app_cours_new')); 
      }



    #[Route('/{id?}', name: 'app_cours_show', methods: ['GET'])]
    #[IsGranted('ROLE_AC', statusCode: 423)]
    public function show(Cours $cour,SessionInterface $session,CoursRepository $coursRepository,
                        ClasseRepository $classeRepository,PlanningRepository $planningRepository,
                        AbsenceRepository $absenceRepository,PaginatorInterface $paginator,
                        Request $request): Response
    {
        // dd($cour);
        $classes= $cour->getClasses()->toArray(); //recupere les classe associe au cour
        // dd($classes);
        $currentCourOfClasse = array_map(function ($cl) use ($classeRepository) {
            return $classeRepository->findBy([
                "id"=> $cl->getId()
            ]) ;
        }, $classes);
         // pour recuperer le libelle des classe associer au cour
        $libelles = [];
        foreach ($currentCourOfClasse as $classeArray) {
            foreach ($classeArray as $classe) {
                $libelles[] = $classe->getLibelle();
            }
        }
        // dd($currentCourOfClasse); 
        
        //stocke id du cour actuel in session
        $currentCour = $coursRepository->find($cour->getId());
        $session->set('cours',$currentCour->getId()); 

        //recupere pour afficher les  sessions planifier du cour
        $sessionOfPlanning = $planningRepository->findBy([ 
            "cours"=> $cour->getId(),
            "isArchived"=> false,
            "state"=> false,
        ]);
        $planningIds = array_map(function($id){
            return  ($id->getId());
        },$sessionOfPlanning);
        $tabAbsences = $absenceRepository->findBy([ 
            "planning"=> $planningIds,
        ]);
        $absences=$paginator->paginate($tabAbsences,$request->query->get('page',1),3);
        // lisste pour afficher les absents de chaque planning
        // dd($tabAbsences);   

        return $this->render('cours/show.html.twig', [
            'cour' => $cour,
            'libelles' => implode(', ', $libelles),
            'sessionOfPlanning' => $sessionOfPlanning,
            'absences' => $absences,
        ]);
    }


    

    #[Route('/{id}/edit', name: 'app_cours_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_AC', statusCode: 423)]
    public function edit(Request $request, Cours $cour, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CoursType::class, $cour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cours_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cours/edit.html.twig', [
            'cour' => $cour,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cours_delete', methods: ['POST'])]
    #[IsGranted('ROLE_AC', statusCode: 423)]
    public function delete(Request $request, Cours $cour, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cour->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cour);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cours_index', [], Response::HTTP_SEE_OTHER);
    }


   
}
