<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Form\ClasseType;
use App\Form\SearchFormClassType;
use Knp\Component\Pager\Paginator;
use App\Repository\ClasseRepository;
use App\Repository\ModuleRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\InscriptionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClasseController extends AbstractController
{
    #[Route('/classes', name: 'liste',methods:['GET'])]
    #[IsGranted('ROLE_AC', statusCode: 423)]
    public function index(ClasseRepository $classeRepository, Request $request,PaginatorInterface $paginator): Response
    {
        $classes=$classeRepository->findBy(['isArchived'=>false]);
    $pagination = $paginator->paginate(
        $classeRepository->y(['isArchived'=>false]),
        $request->query->get('page',1),3
    );
        return $this->render('classe/index.html.twig', [
            'classes' => $classes,
            'pagination' => $pagination,
        ]);
    }


    #[Route('/classe/save/{id?}', name: 'app_classe_save',methods:["GET","POST"])]
    #[IsGranted('ROLE_AC', statusCode: 423)]
    public function saveAndEdit($id,Request $request,EntityManagerInterface $manager,
                                ClasseRepository $classeRepository,
                                SessionInterface $session): Response
    {
        if($id==null){
            $classe=new Classe();
            
          }else{
            $classe=$classeRepository->find($id);
          }
           
           
            $form=$this->createForm(ClasseType::class, $classe);
    
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // dd($form->getData());
                $classe->setLibelle($classe->getNiveau()->getLibelle()." ".$classe->getFiliere()->getLibelle());
                $manager->persist($classe);
                $manager->flush();
                // $this->addFlash("crud_success","Enregistrement de La Classe avec success");
                $session->set('crud_success',"Super vous avez ajouter une nouvelle classe.");
                return $this->redirectToRoute('app_home');
            }
            
        return $this->render('classe/form.html.twig', [
            "form"=> $form->createView()
         ]);
    }
    #[Route('/classe/{id?}/archived', name: 'app_classe_archive',methods:["GET"])]
    #[IsGranted('ROLE_AC', statusCode: 423)]
    public function archive($id,EntityManagerInterface $manager,ClasseRepository $classeRepository): Response
    {
      
            $cl=$classeRepository->find($id);
            
            if ($cl != null) {
                $cl->setIsArchived(true);
                $manager->flush();
            }
            return $this->redirectToRoute('app_home');
    }


    #[Route('/classe/{id?}/info', name: 'app_classe_detail',methods:["GET"])]
    #[IsGranted('ROLE_AC', statusCode: 423)]
    public function showDetails($id,ClasseRepository $classeRepository,InscriptionRepository $inscriptionRepository): Response  {
        $classe = $classeRepository->find($id);
        
        if (!$classe instanceof Classe) {
            throw $this->createNotFoundException('Classe non trouvée.');
        }
        $modules = $classe->getModules()->toArray();
        $enseignants = $classe->getProfesseurs()->toArray();
        $argInscriptions =  $classe->getInscriptions()->toArray();

        $libellesDesModules = array_map(function ($module) {
            return $module->getLibelle();
        }, $modules);

        $nomDesEnseignants = array_map(function ($enseignant) {
            return $enseignant->getNomComplet();
        }, $enseignants);
        // $inscriptions = array_map(function ($inscription) {
        //     return $inscription->getInscriptions();
        // }, $argInscriptions);

        // dd($nomDesEnseignants);
        return $this->render('classe/detail.html.twig', [
            "libellesDesModules"=> $libellesDesModules,
            "nomDesEnseignants"=> $nomDesEnseignants,
            "classe"=> $classe,
         ]);
    }
    
    
    #[Route('/classe/filtre/{id?}', name: 'app_classe_filtre',methods:["GET","POST"])]
    #[IsGranted('ROLE_AC', statusCode: 423)]
    public function filtrer(Request $request,ClasseRepository $classeRepository): Response  {
        $form=$this->createForm(SearchFormClassType::class);
        $form->handleRequest($request);
        $filtre=[   
           
            'isArchived'=>false
        ];
        
        if ($form->isSubmitted()) {
            if( $form->get("niveau")->getData()!=null) {
                $filtre["niveau"]=$form->get("niveau")->getData();
            }
            if( $form->get("filiere")->getData()!=null) {
                $filtre["filiere"]=$form->get("filiere")->getData();
            }
        }
        $classes=$classeRepository->findBy($filtre);
        
        // dd($classes);
        return $this->render('classe/filtrer.html.twig', [
            "classes"=> $classes,
            "form"=> $form->createView()
         ]);
    }
}
