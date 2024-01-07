<?php

namespace App\Controller;

use App\Repository\AnneeScolaireRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    #[Route(path: '/', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils,SessionInterface $session,
                            AnneeScolaireRepository $yearsRepository): Response
    {
        if ($this->getUser()) {
            //charge tous les annee scolaire 
            $loadingYears= $yearsRepository->findAll();
            //charge  annee scolaire en cours
            $currentYear =$yearsRepository->findOneBy([
                    'isActive'=>true
            ]);
            $session->set('years',$loadingYears); 
            $session->set('currentYear',$currentYear); 

            $userConnected = $this->getUser();
            $userRole =$userConnected->getRoles();
      

            switch (true) {
                case in_array('ROLE_AC',$userRole):
                    return $this->redirectToRoute('app_home');
                    break;
                case in_array('ROLE_RP',$userRole):
                    // dd("rp s'est connecter");
                    return $this->redirectToRoute('app_home');
                    break;
                case in_array('ROLE_PROFESSEUR',$userRole):
                    // dd("PROFESSEUR s'est connecter");
                    return $this->redirectToRoute('app_home_other_users');
                    break;
                case in_array('ROLE_ETUDIANT',$userRole):
                    // dd($name);
                    return $this->redirectToRoute('app_home_other_users');
                    break;
                
                default:
                    # code...
                    break;
            }
            

        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        // dd($error);
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
