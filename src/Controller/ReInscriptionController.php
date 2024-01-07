<?php

namespace App\Controller;

use App\Entity\Inscription;
use App\Entity\ReInscription;
use App\Form\ReInscriptionType;
use App\Repository\ClasseRepository;
use App\Repository\NiveauRepository;
use App\Repository\EtudiantRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\InscriptionRepository;
use App\Form\ResearchMatriculeEtudiantType;
use App\Repository\AnneeScolaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReInscriptionController extends AbstractController
{

    #[Route('/re_register/student', name: 'app_reInscription',methods:["GET","POST"])]
    #[IsGranted('ROLE_AC', statusCode: 423)]
    public function index(Request $request,EntityManagerInterface $manager,
    SessionInterface $session,EtudiantRepository $etudiantRepository,
    AnneeScolaireRepository $yearsRepository,
    InscriptionRepository $inscriptionRepository,
    ClasseRepository $classeRepository,
    NiveauRepository $niveauRepository): Response
    { // methode utiliser pour afficher et storer
        if ($session->has('inscription_close')) {
            // dd('impossible');
            return new RedirectResponse($this->generateUrl('app_inscription_store'));
        }
        $etudiant=[];
        $studentData=[];
        $classeSuperieur=[];
        $formMatricule=$this->createForm(ResearchMatriculeEtudiantType::class);
        $formMatricule->handleRequest($request);
        $reInscription=new ReInscription();
        // FORMULAIRE QUI RECHERCHE LE MATRICULE
        $search=null;
        if ($formMatricule->isSubmitted() && $formMatricule->isValid()) {
            $matricule = $formMatricule->get('matricule')->getData();
            $search = $etudiantRepository->findOneBy(["matricule" =>$matricule]);
            $etudiantId=($search != null ? $search->getId() : dd('la recherche est nul'));
            $etudiant[]=$search;
            // dd($search);
            // dd($search->getReInscriptions()->toArray());
            // dd($search->getInscriptions()->toArray());
            // dd($search); 
       
            $infoInscription= ($etudiant != [] ? $etudiant[0]->getInscriptions()->toArray() : []); 
            $studentData = array_merge($etudiant,$infoInscription); 
            $session->set("currentEtudiant",$etudiantId);
            if ($search) {
                $inscriptionsEtudiant=  $search->getInscriptions()->toArray();
                // RECUPERE ID DE LA CLASSE PRECEDENTE
                $classeId=0;
                $old = array_map(function($inscription) use (&$classeId){
                    if ($inscription->isIsArchived() == false) {
                        $classeId=$inscription->getClasse()->getId();
                        return $classeId;
                    }
                },$inscriptionsEtudiant);
                // dd($classeId);
                // $session->set("etu",$inscriptionsEtudiant);
                //   $oldClasse=($inscriptionsEtudiant[0]->getClasse()->getId());
                  $saClasse =$classeRepository->findOneBy([ "id"=> $classeId]);
                //   $saClasse =$classeRepository->findOneBy([ "id"=> $oldClasse]);
                //   dd($saClasse);
                 // recuperez le niveau et   teste sur le niveau actuel
                  if ($saClasse != null) {
                    $oldNiveau =($saClasse->getNiveau()->getId() );
                    switch (true) {
                        case str_contains($saClasse->getLibelle(),'L1'):
                            
                            $licenceDeux = $classeRepository->findBy([ "niveau"=>$oldNiveau +1,
                            "isArchived"=> false]);
                            $classeSuperieur[]=$licenceDeux;
                            // dd($licenceDeux);
                            break;
                        case str_contains($saClasse->getLibelle(),'L2'):
                            // dd('il passe au L3');
                             $licenceTrois = $classeRepository->findBy([ "niveau"=> $oldNiveau +1,
                             "isArchived"=> false]);
                             $classeSuperieur[]=$licenceTrois;
                            // dd($classeSuperieur);
                            break;
                        case str_contains($saClasse->getLibelle(),'L3'):
                            dd("part au master");
                            break;
                        default:
                            # code...
                            break;
                    }                    
                  }
                 
            }
        }
        
        // dd($classeSuperieur);
        //  dd($etudiant);
        if ($request->isMethod('POST')  && $request->request->has('Enregistrer_reinscription')) {
            extract($_POST);
            $currentStudentId=$session->get("currentEtudiant");
            $etudiant=$etudiantRepository->findOneBy([ "id"=>$currentStudentId]);
            $currentYear= $yearsRepository->findOneBy([
                "isActive"=> true
            ]);
            $reInscription->setEtudiant($etudiant);
            // dd($search->getInscriptions()->toArray());
            $reInscription->setAnneeScolaire($currentYear);           
            $nouvelleClasse =$classeRepository->findOneBy([ "id"=> intval($Classe)]);
            $reInscription->setClasse($nouvelleClasse);
            $reInscription->setMontant(floatval($montant));
            // dd(($_POST));
            // dd(floatval($montant));
            // dd($reInscription);
            $oldInscription =($etudiant->getInscriptions()->toArray());
            foreach ($oldInscription as  $value) {
                if ($value->isIsArchived() == false) {
                    $value->setIsArchived(true);
                    // dd($value->isIsArchived());
                }
            }
            $inscriptionEtudiant = new Inscription();
            $inscriptionEtudiant->setEtudiant($etudiant);
            $inscriptionEtudiant->setAnneeScolaire($currentYear);
            $inscriptionEtudiant->setClasse($nouvelleClasse);

            $manager->persist($reInscription);
            $manager->persist($inscriptionEtudiant);
            $manager->flush();
        }
       
        // dd($studentData);
        // dd($etudiant->getReInscriptions()->toArray());
            // dd($etudiant[0]->getInscriptions()->toArray()); 
        return $this->render('re_inscription/form.html.twig', [
            'etudiant' => $etudiant,
            'studentData' => $studentData,
            "formMatricule"=> $formMatricule->createView(),
            // "formReInscription"=> $formReInscription->createView(),
            "classeSuperieur"=> $classeSuperieur,
        ]);
    }
}
// $formReInscription=$this->createForm(ReInscriptionType::class,$reInscription);
// $formReInscription->handleRequest($request);
// if ($formReInscription->isSubmitted() && $formReInscription->isValid()) {
//     $dt=$session->get("etu");
//     $currentStudent = $dt[0]->getEtudiant();

   
//     $reInscription->setAnneeScolaire($yearsRepository->findOneBy([
//         "isActive"=> true
//       ]));
    
//     $reInscription->setEtudiant($etudiantRepository->find($currentStudent->getId()));
//     // $reInscription->setEtudiant($currentStudent);

//     // dd($formReInscription->getData());
//     $manager->persist($reInscription);
//     $manager->flush();
// }
// ------------- RE INSCRIPTION
// $etudiantInsription=  $search->getInscriptions()->toArray();
// $etudiantId=0;
// foreach ($etudiantInsription as  $value) {
//     $etudiantId=$value->getEtudiant()->getId();
// }
// $inscription=$inscriptionRepository->findBy([ "etudiant"=> $etudiantId,
//                                                 "isArchived"=> false,]);
// $prevClass=0;
// foreach ($inscription as   $value) {
//     // dd($value->setIsArchived(false)); j'archive ses acinne inscription
//     $prevClass=$value->getClasse()->getId(); // recuper claasse
// }
// $classe =$classeRepository->findOneBy([ "id"=> $prevClass]);
// $niveauId=$classe->getNiveau()->getId();
// $currentNiveau = $niveauRepository->findOneBy([ "id"=> $niveauId]);
// $libelle = $currentNiveau->getLibelle();
// // dd($libelle); 
// switch (true) {
//     case str_contains('L1',$libelle):
//         dd('il passe au master');
//         $licenceDeux = $classeRepository->findBy([ "niveau"=> 380]);
//         dd($licenceDeux);
//         break;
//     case str_contains('L2',$libelle):
//         // dd('il passe au L3');
//          $licenceTrois = $classeRepository->findBy([ "niveau"=> 381]);
//         // dd($licenceTrois);
//         break;
//     case str_contains('L3',$libelle):
//         dd("part au master");
//         break;
//     default:
//         # code...
//         break;
// }
// dd($currentNiveau->getLibelle());