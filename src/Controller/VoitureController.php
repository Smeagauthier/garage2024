<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Form\VoitureType;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VoitureController extends AbstractController
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/voitures', name: 'voitures')]
    public function index(VoitureRepository $repo): Response
    {
        $voitures = $repo->findAll();
        return $this->render('voiture/index.html.twig', [
            'voitures' => $voitures,
        ]);
        
    }
    
    #[Route("/voiture/{id}", name :"voiture_show")]
    public function show($id, EntityManagerInterface $entityManager): Response
    {
        $voiture = $entityManager->getRepository(Voiture::class)->find($id);
        
        if (!$voiture) {
            throw $this->createNotFoundException('Voiture non trouvée');
        }
        
        return $this->render('voiture/show.html.twig', [
            'voiture' => $voiture,
        ]);
    }
    
    #[Route('/voitures/new', name: 'voitures_create')]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $voiture = new Voiture();
        
        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            //Gestion des images à faire
            foreach ($voiture->getImages() as $image) {
                $image->setVoiture($voiture);
                $manager->persist($image);
            }
            
            $manager->persist($voiture); 
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre annonce pour la <strong>'.$voiture->getMarque().' '.$voiture->getModele().'</strong>a bien été enregistrée !');
                
                //Redirection vers la page de la voiture
                return $this->redirectToRoute('voiture_show', [
                    'id' => $voiture->getId()
                ]);
                
            }
            
            return $this->render("voiture/new.html.twig",[
                'myForm' => $form->createView()
            ]);
            
        }   
    }
