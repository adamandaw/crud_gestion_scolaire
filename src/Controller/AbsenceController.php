<?php

namespace App\Controller;

use App\Entity\Absence;
use App\Form\AbsenceType;
use App\Repository\CoursRepository;
use App\Repository\AbsenceRepository;
use App\Repository\EtudiantRepository;
use App\Repository\PlanningRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\InscriptionRepository;
use App\Repository\AnneeScolaireRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/absences')]
class AbsenceController extends AbstractController
{
    #[Route('/', name: 'app_absence_index', methods: ['GET','POST'])]
    public function index(AbsenceRepository $absenceRepository): Response
    {
      
        return $this->render('absence/index.html.twig', [
            'absences' => $absenceRepository->findAll(),
        ]);
    }

    #[Route('/cours/{id}/new', name: 'app_absence_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_PROFESSEUR')]
    public function new($id,Request $request, EntityManagerInterface $entityManager,
    PlanningRepository $planningRepository, CoursRepository $coursRepository,
    InscriptionRepository $inscriptionRepository,AnneeScolaireRepository $yearsRepository,
    SessionInterface $session,EtudiantRepository $etudiantRepository,
    PaginatorInterface $paginator): Response
    {
       
       
        
       $planningId= intval($id);
       
    //    recherche le planning actuel
        $dataPlanning=$planningRepository->findOneBy([
            "id"=>$planningId,
        ]);
        // dd($dataPlanning);
        // recupere id de ce cour
        $currentCour =$dataPlanning->getCours();
        // dd(($currentCour));
        // dd(());

        // recupere lobjet  cour actuel
        $classeOfCourses = $coursRepository->findOneBy([   
            "id"=> $currentCour->getId(),
       ]);
    //    dd(($classeOfCourses));
       $classes=$classeOfCourses->getClasses()->toArray();
    //    dd(gettype($classes));
        
       $listOfStudents = array_map(function($cl){
                return $cl->getId();
       },$classes);

    //    dd(($listOfStudents));
    //    recherche les etudiants
       $currentYear = $yearsRepository->findOneBy(["isActive"=>true]);
       $inscrits=$inscriptionRepository->findBy([
        'anneeScolaire' => $currentYear,
        'classe' => $listOfStudents,
        'isArchived' => false,
        ]);
        $etudiants = $paginator->paginate($inscrits,$request->query->get('page',1),4);

        if ($request->isMethod('POST')  && $request->request->has('etudiant')) { 
            $etudiantId = $_POST['etudiant'];
            $currentEtudiant= $etudiantRepository->findOneBy(['id' => $etudiantId]);
           //  dd($currentEtudiant);
            
             $serializedEtudiant = ($currentEtudiant->getNomComplet());
            //  dd($serializedEtudiant);
            $data = [];
            if ($session->has('etudiant_absent')) {
                // Si la clé 'etudiant_absent' existe déjà dans la session
                $oldEtu = $session->get('etudiant_absent');
                $data = $oldEtu;
            }

            $data[] = $serializedEtudiant;
            //
            $session->set('etudiant_absent', $data);
       }
       if ($request->isMethod('POST')  && $request->request->has('publier')) {
        extract($_POST);
        $students= $session->get('etudiant_absent');

        $currentStudents= $etudiantRepository->findBy(['nomComplet' => $students]);
        foreach ($currentStudents as $student) {
            $absence = new Absence();
            $absence->setPlanning($dataPlanning); // planning actuel
            $absence->setEtudiant($student);
            // $absence->setCreateAt($dataPlanning->getCreateAt());
            $entityManager->persist($absence);
        }
        $entityManager->flush();
        $session->remove('etudiant_absent');
        return $this->redirectToRoute('app_home_other_users', [], Response::HTTP_SEE_OTHER);

        // dd(($currentStudents));
        
       }

       
        
       

        return $this->render('absence/index.html.twig', [
            'etudiants' => $etudiants,
            'planningId' => $planningId,
            // 'form' => $form,
        ]);
    }

    #[Route('/etudiant/{email?}', name: 'app_absence_save', methods: ['GET','POST'])]
    public function save(Request $request,EtudiantRepository $etudiantRepository,SessionInterface $session,$email): Response
    {
        // $email = $request->query->get('email');
        // dd($email);
            
        $etudiant = $etudiantRepository->findOneBy(['email' => $email ]);
        // dd($etudiant);
        $dataToArray =json_encode($etudiant);
        // dd(($dataToArray));
        // $session->set('etudiant',$dataToArray);
        // $dataToObj =json_decode($dataToArray,false);
        // dd(($dataToObj));
    //    return $this->redirectToRoute('app_absence_new');
        return $this->render('absence/index.html.twig', [
            'absence' => $email,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_absence_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Absence $absence, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AbsenceType::class, $absence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_absence_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('absence/edit.html.twig', [
            'absence' => $absence,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_absence_delete', methods: ['POST'])]
    public function delete(Request $request, Absence $absence, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$absence->getId(), $request->request->get('_token'))) {
            $entityManager->remove($absence);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_absence_index', [], Response::HTTP_SEE_OTHER);
    }
}
