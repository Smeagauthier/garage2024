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
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Knp\Component\Pager\PaginatorInterface;

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
    public function create(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger): Response
    {
        $voiture = new Voiture();
        
        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            foreach($voiture->getImages() as $image){
                $image->setVoiture($voiture);
                $manager->persist($image);
            }
               
            // Récupérer l'image depuis le formulaire
            $imageFile = $form->get('coverImage')->getData();

            if (!$form->get('coverImage')->getData()) {
                $this->addFlash('error', 'Veuillez insérer une image de couverture.');
                return $this->render('voiture/new.html.twig', [
                    'myForm' => $form->createView(),
                ]);
            }
            
            if ($imageFile) {
                // Générer un nom unique pour l'image
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                
                // Déplacer l'image dans le répertoire public/uploads/images
                try {
                    $imageFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer l'erreur si le fichier ne peut pas être déplacé
                    throw new \Exception('Une erreur est survenue lors du téléchargement de l\'image.');
                }
                
                // Mettre à jour le champ image de la voiture avec le nouveau nom de fichier
                $voiture->setCoverImage($newFilename);
            }
            
            // Persist et flush l'entité voiture
            $manager->persist($voiture); 
            $manager->flush();
            
            // Message de succès et redirection
            $this->addFlash('success', 'Votre annonce a bien été créée !');

            return $this->redirectToRoute('voiture_show', ['id' => $voiture->getId()]);

        }


        
        return $this->render('voiture/new.html.twig', [
            'myForm' => $form->createView(),
        ]);
    }
}
