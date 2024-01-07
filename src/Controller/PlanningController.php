<?php

namespace App\Controller;

use App\Entity\Cours;
use DateTimeImmutable;
use App\Entity\Planning;
use App\Form\PlanningType;
use App\Repository\CoursRepository;
use App\Repository\PlanningRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/planning')]
#[IsGranted('ROLE_AC')]
class PlanningController extends AbstractController
{
    #[Route('/', name: 'app_planning_index', methods: ['GET'])]
    public function index(PlanningRepository $planningRepository): Response
    {
        return $this->render('planning/index.html.twig', [
            'plannings' => $planningRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_planning_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,
                SessionInterface $session,CoursRepository $coursRepository): Response
    {
       
        // dd(($ses));
        $ses=$session->get('cours'); 
        $currentCour = $coursRepository->find($ses);
        $planning = new Planning();
        $form = $this->createForm(PlanningType::class, $planning);
        $form->handleRequest($request);
        // dd(($form->getData()));
        
        $planning->setCours($currentCour);
        if ($form->isSubmitted() && $form->isValid()) {
            $beginHour = $form->get('beginAt')->getData();
            $endHour = $form->get('endAt')->getData();
            $currentDate = new \DateTimeImmutable();
            $beginHour = $beginHour->setDate($currentDate->format('Y'), $currentDate->format('m'), $currentDate->format('d'));
            $endHour = $endHour->setDate($currentDate->format('Y'), $currentDate->format('m'), $currentDate->format('d'));

            // Calculer la différence en heures
            $diff = $endHour->diff($beginHour);
            $diffInHours = $diff->h;
            
            $id=$currentCour->getId();
            $nbrHeurePlannifier=$currentCour->getNbrHeurePlanifier();

            if ($nbrHeurePlannifier == 0) { // 1er planification
                $nbrHeurePlannifier=$diffInHours;
            }else { // 2,3,4 planification
                // dd("OK");
                $ancienneHeurePlanifier=$currentCour->getNbrHeurePlanifier();
                $heureGlobale=$currentCour->getNbrHeureGlobal();
                $nbrHeurePlannifier=$ancienneHeurePlanifier + $diffInHours;
                // dd($ancienneHeurePlanifier);
                
                // dd($heureRestante);
                if ($nbrHeurePlannifier > $heureGlobale) {
                    // dd($ancienneHeurePlanifier);
                    // dd("On peut plus planifier ce cour heure globale atteinte");
                    $heureRestante=$heureGlobale - $ancienneHeurePlanifier;
               
                    $session->set('hour_error',"Il vous reste ".$heureRestante." h pour une planification de ce cours."); 
                        return $this->redirectToRoute('app_planning_new');
                }  
            }
            // dd('toto');
            $entityManager->persist($planning);
            $coursRepository->majNbrHeure($id,$nbrHeurePlannifier,'nbrHeurePlanifier');
            
            $entityManager->flush();
            $session->remove('cours');
            //   dd($da);
            $session->set('crud_planning_success',"Vous avez planifier ".$nbrHeurePlannifier."h.");
                // $this->addFlash("crud_planning_success","");
            return $this->redirectToRoute('app_cours_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('planning/new.html.twig', [
            'planning' => $planning,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/trashed', name: 'app_planning_trash',methods:["GET"])]
    public function archive($id,PlanningRepository $planningRepository,
    EntityManagerInterface $manager,SessionInterface $session,
    CoursRepository $coursRepository): Response
    {
      
            $currentPlanning=$planningRepository->findOneBy(["id"=>$id]);
           
            // dd($diff);
            // dd($finalHour);
            if ($currentPlanning != null) {   
                $currentPlanning->setIsArchived(true);
                $courId=$currentPlanning->getCours()->getId();

                $currentCour=$coursRepository->findOneBy(["id"=>$courId]);
                $beginAt=$currentPlanning->getBeginAt();
                $endAt=$currentPlanning->getEndAt();

                $numberOfHourPlanifer=$currentCour->getNbrHeurePlanifier();
                $diff = $endAt->diff($beginAt)->h;
                $finalHour=$numberOfHourPlanifer - $diff;
                $value = strval($finalHour);
                if (str_contains($value,'-')) {
                    $valueWithoutSign = str_replace('-', '', $value);
                    $finalHour = intval($valueWithoutSign);
                }
                $coursRepository->majNbrHeure($courId,$finalHour,'nbrHeurePlanifier');
                $manager->flush();
                // return $this->redirectToRoute('app_cours_show');
                return  new Response("ok");
            }
            throw $this->createNotFoundException('La Planification na pas ete trouvée.'); //apres mettre dans la ssesion
            
    }


    #[Route('/{id}/validate', name: 'app_planning_validate',methods:["GET"])]
    public function valider($id,PlanningRepository $planningRepository,EntityManagerInterface $manager,SessionInterface $session,CoursRepository $coursRepository): Response
    {
      
            $currentPlanning=$planningRepository->findOneBy(["id"=>$id]);
           
           
            // dd($finalHour);
            if ($currentPlanning != null) {   
                $currentPlanning->setState(true);
                $courId=$currentPlanning->getCours()->getId();
                
                $currentCour=$coursRepository->findOneBy(["id"=>$courId]);
                
                $beginAt=$currentPlanning->getBeginAt();
                $endAt=$currentPlanning->getEndAt();
                $numberTimePerform=$currentCour->getNbrHeureEffectuer();
                $heureGlobale=$currentCour->getNbrHeureGlobal();
                $diff = $endAt->diff($beginAt); //calcule lheure
                // dd($diff);
                $finalHour= $diff->h; // h est une methode qui retourne uniquement heure ex : m = minute ; y = annee; w = semaine etc dd pour voir les info 
                if ($numberTimePerform == 0) { // 1er heures effectuer
                    $nbrHeureEffectuer=$finalHour;
                    
                }else { // 2,3,4 heures effectuer
                    
                    $ancienneHeurePlanifier=$currentCour->getNbrHeureEffectuer();
                    //   dd($ancienneHeurePlanifier);
                  
                    $nbrHeureEffectuer= $finalHour + $ancienneHeurePlanifier;
                   
                    // dd($nbrHeureEffectuer);
                }
               
                $coursRepository->majNbrHeure($courId,$nbrHeureEffectuer,'nbrHeureEffectuer');
                if ($heureGlobale == $nbrHeureEffectuer) {
                    $currentCour->setIsDone(true);
                }
                $manager->flush();
                // return $this->redirectToRoute('app_cours_show');
                return  new Response("ok");
            }
            throw $this->createNotFoundException('La Planification na pas ete trouvée.'); //apres mettre dans la ssesion
            
    }



    #[Route('/{id}', name: 'app_planning_show', methods: ['GET'])]
    #[IsGranted('ROLE_AC', statusCode: 423)]
    public function show(Planning $planning): Response
    {
        return $this->render('planning/show.html.twig', [
            'planning' => $planning,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_planning_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Planning $planning, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlanningType::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_planning_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('planning/edit.html.twig', [
            'planning' => $planning,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_planning_delete', methods: ['POST'])]
    public function delete(Request $request, Planning $planning, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$planning->getId(), $request->request->get('_token'))) {
            $entityManager->remove($planning);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_planning_index', [], Response::HTTP_SEE_OTHER);
    }
}
