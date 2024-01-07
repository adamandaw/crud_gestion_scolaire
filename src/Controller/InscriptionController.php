<?php

namespace App\Controller;

use DateTime;
use App\Entity\Etudiant;
use App\Entity\Inscription;
use App\Form\InscriptionType;
use App\Form\FilterByYearsType;
use App\Repository\UserRepository;
use App\Repository\ClasseRepository;
use App\Repository\EtudiantRepository;
use App\Form\ResearchInscritsClasseType;
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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class InscriptionController extends AbstractController
{

    private $encoder;
    private PaginatorInterface $paginator;
        public function __construct(UserPasswordHasherInterface $encoder,PaginatorInterface $paginator){
                    $this->encoder=$encoder;
                    $this->paginator=$paginator;
        }

    #[Route('/inscriptions', name: 'app_inscription',methods:["GET","POST"])]
    #[IsGranted('ROLE_AC', statusCode: 423)]
    public function index(InscriptionRepository $inscriptionRepository,ClasseRepository $classeRepository,
                          SessionInterface $session,AnneeScolaireRepository $yearsRepository,Request $request): Response
    {
        // $= $yearsRepository->findAll();
        $currentYear = $session->get('currentYear');
        $loadingYears = $session->get('years');
        // $pagination = $paginator->paginate($classes,$request->query->get('page',1),3);
        $inscrits=$inscriptionRepository->findBy([
            'anneeScolaire' => $currentYear,
            'isArchived' => false
        ]);
        // dd($inscrits);
        $inscritsOfThisYear=$this->paginator->paginate($inscrits,$request->query->get('page',1),3);

        // filtre par classe
        $tabClasseFiltrer=[];
        $formListClasses=$this->createForm(ResearchInscritsClasseType::class);
         
        $formListClasses->handleRequest($request);
        if ($formListClasses->isSubmitted() && $formListClasses->isValid()) {
            // dd($formListClasses->getData());
            $selectedClasse = $formListClasses->get('classes')->getData();
            $tabClasseFiltrer = $inscriptionRepository->findBy([
                'anneeScolaire' => $currentYear,
                'isArchived' => false,
                'classe' => $selectedClasse,       
            ]);
        }
        $classes=$classeRepository->findBy([
            'isArchived' => false
        ]);

        // filtre par annee
        $findor=[];
        $formSelectorYear=$this->createForm(FilterByYearsType::class);
        $formSelectorYear->handleRequest($request);
        if ($formSelectorYear->isSubmitted() && $formSelectorYear->isValid()) {
             $yearSelected=$formSelectorYear->getData();
             $findor=$inscriptionRepository->findBy([
              'isArchived' => false,
              'anneeScolaire' => $yearSelected,
          ]);
            
             $yearLibelle = array_map(function ($annee) {
                  return $annee->getAnneeScolaire()->getLibelle();
              }, $findor);
             dd($findor); 
            $session->set('anneeFiltrer',$yearLibelle[0]);
            // $selectedYear = $formSelectorYear->get('classes')->getData();
            
        }
       
        // dd($inscrits);
        return $this->render('inscription/index.html.twig', [
            'inscrits' => $inscritsOfThisYear,
            'classes' => $classes,
            // 'loadingYears' => $loadingYears,
            // filtre par classe
            'formListClasses' => $formListClasses->createView(),
            'tabClasseFiltrer' => $tabClasseFiltrer,
            // filtre par annee
            'formSelectorYear' => $formSelectorYear->createView(),
            'findor' => $findor,
        ]);
    }

    #[Route('/inscriptions/new/{id?}', name: 'app_inscription_store',methods:["GET","POST"])]
    #[IsGranted('ROLE_AC', statusCode: 423)]
    public function store($id,InscriptionRepository $inscriptionRepository,Request $request,
                          EntityManagerInterface $manager,AnneeScolaireRepository $yearsRepository,
                          SessionInterface $session,UserRepository $userRepository): Response
    {
        if($id==null){
            $endPeriod=$yearsRepository->findOneBy(['isActive' => true]);
            // $finDePeriod=$endPeriod->getPeriodInscriptionAt()->format('d-m-Y');
            $finDePeriod=$endPeriod->getPeriodInscriptionAt();
            // Obtenir la date et l'heure actuelles
            $toDay = new DateTime();
            // dd($finDePeriod);
            // Comparer les deux dates
            $dateInterval = $finDePeriod->diff($toDay);
            // dd($dateInterval);
            if (!$dateInterval->invert) {
                // dd("La date est déjà dépassée.");
                $session->set('inscription_close',"Fin de période : ".$finDePeriod->format('d-m-Y'));
            } 
            $inscription=new Inscription();
            $etudiant=new Etudiant();
    
          }else{
            $inscription=$inscriptionRepository->find($id);
          }

          $form=$this->createForm(InscriptionType::class, $inscription);
    
          $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()) {
            $inscription->setAnneeScolaire($yearsRepository->findOneBy([ 
              "isActive"=> true
            ]));
            // $etudiant->setLieuDeNaissance($form->get("lieuDeNaissance")->getData());
           
            // recurperer etudiant
            $etudiant=$inscription->getEtudiant();
           
            // $matricule=$etudiant->getMatricule();    
            $currentYear = $session->get('currentYear');
            $mat="ISM".$currentYear->getLibelle()."/".uniqid();
            $etudiant->setMatricule($mat);

            // email
            $chiffreAleatoire = rand(1, 20);
            $nomSansEspace = str_replace(' ', '', $etudiant->getNomComplet());
            $nomAvecUnderscore=str_replace(' ', '_', $etudiant->getNomComplet());
            $emailGenerated=$nomSansEspace.$chiffreAleatoire."@gmail.com";
            $testEmail= $userRepository->findOneBy([
                'email'=>$etudiant->getEmail()
            ]);
            if ($testEmail) {      // email est identique
                $chiffreAleatoire+=8;
                $emailGenerated=$nomAvecUnderscore.$chiffreAleatoire."@gmail.com";
                // dd($emailGenerated);
            } 
            $etudiant->setEmail(strtolower($emailGenerated));

            // password
            $plainPassword = 'passer';
              $encoded = $this->encoder->hashPassword($etudiant,$plainPassword);
              $etudiant->setPassword($encoded);
            //   $inscription->
             
            //   dd($form->getData());
              $manager->persist($inscription);
              $manager->flush();

            //   $this->addFlash("crud_success",$inscription->getNomComplet()."  enregistrer avec success");
              
                $session->set('crud_success', $etudiant->getNomComplet()." à été inscrit avec success.");
              return $this->redirectToRoute('app_home');
          }
        return $this->render('inscription/form.html.twig', [
            "form"=> $form->createView()
        
        ]);
    }


    #[Route('inscriptions/etudiant-{id}', name: 'app_inscription_show', methods: ['GET'])]
    #[IsGranted('ROLE_AC', statusCode: 423)]
    public function show($id,InscriptionRepository $inscriptionRepository,
                        ClasseRepository $classeRepository
                        ,EtudiantRepository $etudiantRepository,): Response
    {
        $currentStudent = $etudiantRepository->findOneBy([
            'id' => $id,]);
        // dd($currentStudent);
        $absences = $currentStudent->getAbsences()->toArray();
        $inscriptions = $currentStudent->getInscriptions()->toArray();
        $reInscriptions = $currentStudent->getReInscriptions()->toArray();
        // dd($absences);
        // $detailsEtudiants = array_merge($inscriptions,$absences);
        // dd($inscriptions);
        // dd($search->getReInscriptions()->toArray());
            // dd($search->getInscriptions()->toArray());
        
        //stocke id du cour actuel in session
        
        // dd($sessionOfPlanning);
       
        
        return $this->render('inscription/show.html.twig', [
            'inscriptions' => $inscriptions,
            'absences' => $absences,
            // 'libelles' => implode(', ', $libelles),
            // 'sessionOfPlanning' => $sessionOfPlanning,
        ]);
    }


}
