<?php

namespace App\Controller;

use App\Entity\Marque;
use App\Repository\MarqueRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MarquesController extends AbstractController
{

    // affiche toutes les marques
    #[Route('/marques', name: 'marquepage')]
    public function index(MarqueRepository $repo): Response
    {
        $marque = $repo->findAll();
        return $this->render('marques/index.html.twig', [
            'marque' => $marque,
        ]);
    }

    
    // Permet d'afficher toutes les voitures d'une seule marque
     
    #[Route('/marques/{id}', name: 'voiture_marque')]
    public function marquesShow(Marque $marque): Response
    {
        return $this->render('voitures/marquevoiture.html.twig',[
            'marque' => $marque
        ]);
    }
}
