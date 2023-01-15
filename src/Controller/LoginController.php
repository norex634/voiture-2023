<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    // Page vers le formulaire de cconnexion 
    #[Route('/login', name: 'account_login')]
    public function index(AuthenticationUtils $utils): Response
    {   
        // prend l'erreur s'il y en a une
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        return $this->render('login/index.html.twig', [
                // affiche l'erreur si il y en a une
                'hasError' => $error !== null,
                'username' =>  $username
        ]);

    }
    /**
     * Permet à l'utilisateur de se déconnecter
     *
     * @return void
     */
    #[Route("/logout", name:"account_logout")]
    public function logout(): void
    {
        // ..
    }
}
