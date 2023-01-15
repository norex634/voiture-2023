<?php

namespace App\Controller;


use App\Entity\Voiture;
use App\Form\SearchType;
use App\Form\VoitureType;
use App\Form\VoitureModifyType;
use App\Repository\ImageRepository;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class VoituresController extends AbstractController
{
    /**
     * Permet d'ajouter une nouvelle voiture
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route("/voitures/new", name:"voitures_new")]
    #[IsGranted("ROLE_ADMIN")]
    public function create(Request $request,EntityManagerInterface $manager): Response
    {
      $voiture = new Voiture();  
      $form = $this->createForm(VoitureType::class, $voiture);
      $form->handleRequest($request);

       
        if($form->isSubmitted() && $form->isValid())
        {   
            foreach($voiture->getImages() as $image){

               $image->setVoiture($voiture);
                $manager->persist($image);
                
            }

            $file = $form['cover']->getData();
            if(!empty($file))
            {
                $originalFilename = pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin;Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename."-".uniqid().".".$file->guessExtension();
                try{
                    $file->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                }catch(FileException $e)
                {
                    return $e->getMessage();
                }
                $voiture->setCover($newFilename);
            }
                // $cover->setCoverImg($voiture);
                // $manager->persist($cover);

            $manager->persist($voiture);
            $manager->flush();

            $this->addFlash(
                'success',
                "La voiture <strong>{$voiture->getModele()}</strong> a bien été enregistrée!"
            );

            return $this->redirectToRoute('voiture_show', [
                'slug' => $voiture->getSlug()
            ]);
        }
       
    return $this->render('voitures/new.html.twig', [
        // 'hasError'=>$error !== null,
        'form' => $form->createView()
    ]);
    }

    /**
     * Permet de modifier une annonce de voiture
     */
    #[Route("/voitures/{slug}/edit", name:'voiture_edit')]
    #[Security("is_granted('ROLE_ADMIN')", message:"Cette annonce ne vous appartient pas, vous ne pouvez pas la modifier")]
    public function edit(Request $request, EntityManagerInterface $manager, Voiture $voitures):Response
    {
        $fileName = $voitures->getCover();
        if(!empty($fileName))
        {
            new File($this->getParameter('uploads_directory').'/'.$voitures->getCover());
        }

        $form = $this->createForm(VoitureModifyType::class, $voitures);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {           
                $voitures->setCover($fileName);

                $voitures->setSlug('');
                $manager->persist($voitures);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "L'annonce <strong>{$voitures->getModele()}</strong> a bien été modifiée"
                );

                return $this->redirectToRoute('voiture_show',['slug'=>$voitures->getSlug()]);
        }


        return $this->render("voitures/edit.html.twig",[
            
            "form" => $form,
            "voitures"=>$voitures
        ]);
    }

    /**
     * Permet de supprimer une voiture
     */
    #[Route('/ventes/{slug}/delete', name:"voiture_delete")]
    #[Security("(is_granted('ROLE_ADMIN'))",message:'Cette voitures ne vous appartient pas')]
    public function delete(Voiture $voiture, EntityManagerInterface $manager): Response
    {
        if(!empty($voiture->getCover()))
        {
            unlink($this->getParameter('uploads_directory').'/'.$voiture->getCover());
            $voiture->setCover('');

            
            
            $manager->flush();
           
        }
        
        foreach($voiture->getImages() as $image){
            
            // $image->removeImage($voiture);
            $manager->remove($image); 
            
            
             
         }

        $this->addFlash(
            'success',
            "La voiture <strong>{$voiture->getModele()}</strong> a bien été supprimée"
        );

        $manager->remove($voiture);
        $manager->flush();

        return $this->redirectToRoute('voiturepage');
    }

    #[Route('/voitures', name: 'voiturepage')]
    public function index(VoitureRepository $repo): Response
    {
        $voiture = $repo->findAll();
            

        return $this->render('voitures/index.html.twig', [
            
            'voiture' => $voiture,
        ]);
    }


    // afficher la page de la voiture selectionner 
    #[Route('/voitures/{slug}', name:'voiture_show')]
    public function show(string $slug, Voiture $voiture,ImageRepository $image):Response
    {
       $img = $image->findAll();
       

        return $this->render('voitures/show.html.twig',[
            'voiture' => $voiture,
            'image'=> $img
            
        ]);
    }

    
}
