<?php

namespace App\Controller;

use App\Entity\Professeur;
use App\Form\ProfesseurType;
use App\Repository\ProfesseurRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ResearchProfesseurGradeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfesseurController extends AbstractController
{
    private $encoder;
    private ProfesseurRepository $professeurRepository;
    public function __construct(UserPasswordHasherInterface $encoder,ProfesseurRepository $professeurRepository)
    {
        $this->encoder=$encoder;
        $this->professeurRepository=$professeurRepository;
    }

    #[Route('/professeurs', name: 'professeur')]
    public function index(ProfesseurRepository $professeurRepository): Response
    {
        $profs=$professeurRepository->findAll();
        return $this->render('professeur/index.html.twig', [
            'profs' => $profs,
        ]);
    }


    #[Route('/professeur/new/{id?}', name: 'app_professeur_save',methods:["GET","POST"])]
    #[IsGranted('ROLE_AC', statusCode: 423)]
    public function saveAndEdit($id,Request $request,EntityManagerInterface $manager,
                                ProfesseurRepository $professeurRepository,
                                SessionInterface $session): Response
    {
        // $this->denyAccessUnlessGranted("ROLE_AC"); // refuse laccès tant que le role nest op un AC
        if($id==null){
            $prof=new Professeur();
    
          }else{
            $prof=$professeurRepository->find($id);
          }
           
           
            $form=$this->createForm(ProfesseurType::class, $prof);
    
            $form->handleRequest($request);
        //   dd($form);
            if ($form->isSubmitted() && $form->isValid()) {    
                $plainPassword = 'passer';
                $encoded = $this->encoder->hashPassword($prof,$plainPassword);
                $prof->setPassword($encoded);
                $manager->persist($prof);
                $manager->flush();

                // $this->addFlash("crud_success",$prof->getNomComplet()." à été enregistrer avec success");
                $session->set('crud_success',"Super ". $prof->getNomComplet()." à été enregistrer avec success");
                return $this->redirectToRoute('app_home');
            }
        return $this->render('professeur/form.html.twig', [
            "form"=> $form->createView()
         ]);
    }
    #[Route('/professeur/{id?}/details', name: 'app_professeur_detail',methods:["GET"])]
    #[IsGranted('ROLE_AC', statusCode: 423)]
    public function details($id,ProfesseurRepository $professeurRepository): Response
    {
        $professeur = $professeurRepository->find($id);
        //  dd($professeur);
        if (!$professeur instanceof professeur) {
            throw $this->createNotFoundException('Professeur non trouvée.');
        }
        $modules = $professeur->getModules()->toArray();
        $classesDuProf = $professeur->getClasses()->toArray();
 
        $libellesDesModules = array_map(function ($module) {
            return $module->getLibelle();
        }, $modules);
        // dd($libellesDesModules);
        $libelleDesClasse = array_map(function ($libelleCl) {
            return $libelleCl->getLibelle();
        }, $classesDuProf);
        // dd($classesDuProf);
        // dd($professeur);
        return $this->render('professeur/detail.html.twig', [
            "libellesDesModules"=> $libellesDesModules,
            "libelleDesClasse"=> $libelleDesClasse,
            "professeur"=> $professeur,
         ]);
    }
    public function findModule($id=null,ProfesseurRepository $professeurRepository): array
    {
        $professeur = $professeurRepository->find($id);
        if (!$professeur instanceof professeur) {
            throw $this->createNotFoundException('Professeur non trouvée.');
        }
        $modules = $professeur->getModules()->toArray();

        $libellesDesModules = array_map(function ($module) {
            return $module->getLibelle();
        }, $modules);
        
        // dd($libellesDesModules);
        // dd($professeur);
        return $libellesDesModules;
    }
   

}
