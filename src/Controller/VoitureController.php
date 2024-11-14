<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Voiture;
use App\Form\VoitureType;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class VoitureController extends AbstractController
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * Affiche la liste des voitures.
     *
     * Cette fonction récupère toutes les voitures depuis le repository et les affiche
     * sur la page d'index correspondante.
     *
     * @param VoitureRepository $repo Le repository des voitures.
     * @return Response La réponse contenant la vue de la page d'index avec les voitures.
     */
    #[Route('/voitures', name: 'voitures')]
    public function index(VoitureRepository $repo): Response
    {
        $voitures = $repo->findAll();

        //Méthode render pour afficher la page du template de manière dynamique en fonction des données passées
        return $this->render('voiture/index.html.twig', [
            'voitures' => $voitures,
        ]);
        
    }
    
    /**
     * Affiche les détails d'une voiture spécifique.
     *
     * Cette fonction permet d'afficher les détails d'une voiture en fonction de son identifiant (id).
     * Si la voiture n'est pas trouvée, une exception est levée.
     *
     * @param int $id L'identifiant de la voiture à afficher.
     * @param EntityManagerInterface $entityManager Le gestionnaire d'entités pour récupérer la voiture.
     * @return Response La réponse contenant la vue de la page de détails de la voiture.
     */
    #[Route("/voiture/{id}", name :"voiture_show")]
    public function show($id, EntityManagerInterface $entityManager): Response
    {
        $voiture = $entityManager->getRepository(Voiture::class)->find($id);
        
        // Vérifier si la voiture existe
        if (!$voiture) {
            throw $this->createNotFoundException('Voiture non trouvée');
        }
        
        return $this->render('voiture/show.html.twig', [
            'voiture' => $voiture,
        ]);
    }
    
    /**
     * Crée une nouvelle voiture et gère l'upload de son image de couverture.
     *
     * @param Request $request La requête HTTP.
     * @param EntityManagerInterface $manager Le gestionnaire d'entités.
     * @param SluggerInterface $slugger Le service pour créer des slugs à partir des noms de fichiers.
     * @return Response La réponse avec le formulaire de création ou la redirection après soumission réussie.
     */
    #[Route('/voitures/new', name: 'voitures_create')]
    public function create(Request $request, EntityManagerInterface $manager, SluggerInterface $slugger): Response
    {
        $voiture = new Voiture(); 
        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            //Gestion de l'ajout de une ou plusieurs images
            foreach($voiture->getImages() as $image)
            {
                $image->setVoiture($voiture);
                $manager->persist($image);
            }
            
            // Récupérer l'image depuis le formulaire (obligé pour FileType), getData() renvoie un objet UploadedFile qui permet de récupérer les infos du fichier (nom, extension,...) et de pouvoir les modifier (ici on renomme l'image avec un nom unique pour éviter les conflits et on la déplace dans le dossier public/uploads)
            $imageFile = $form->get('coverImage')->getData();
            
            if (!$form->get('coverImage')->getData()) {
                $this->addFlash('error', 'Veuillez insérer une image de couverture.');
                return $this->render('voiture/new.html.twig', [
                    'myForm' => $form->createView(),
                ]);
            }
            
            if ($imageFile) {
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
            
            $manager->persist($voiture); 
            $manager->flush();
            
            $this->addFlash('success', 'Votre annonce a bien été créée !');
            return $this->redirectToRoute('voiture_show', ['id' => $voiture->getId()]);
            
        }
        
        return $this->render('voiture/new.html.twig', [
            'myForm' => $form->createView(),
        ]);
    }


    /**
     * Modifie une voiture existante, avec possibilité de mettre à jour son image de couverture.
     * 
     * Cette méthode permet à un utilisateur de modifier les informations d'une voiture existante. Si une nouvelle
     * image est soumise, l'ancienne image de couverture est supprimée avant de sauvegarder la nouvelle. 
     * Les autres images associées à la voiture sont également enregistrées.
     *
     * @param Request $request La requête HTTP.
     * @param EntityManagerInterface $manager Le gestionnaire d'entités.
     * @param Voiture $voiture L'entité Voiture à modifier.
     * @return Response La réponse avec le formulaire de modification ou la redirection après soumission réussie.
     */
    #[Route("voiture/{id}/edit", name:"voiture_edit")]
    public function edit(Request $request, EntityManagerInterface $manager, Voiture $voiture): Response
    {
        // Stocker l'ancienne image pour après décider s'il faut la supprimer ou non
        $ancienneImage = $voiture->getCoverImage();
        
        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('coverImage')->getData();
            
            //Si une nouvelle image est téléchargée, on supprime l'ancienne image du répertoire
            if ($imageFile) {

                if ($ancienneImage) {
                    $this->removeImage($ancienneImage);
                }
                
                // Traitement de la nouvelle image
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                $imageFile->move($this->getParameter('uploads_directory'), $newFilename);
                $voiture->setCoverImage($newFilename);

            } else {

                // Si aucune nouvelle image n'est soumise, on conserve l'ancienne
                if ($ancienneImage) {
                    $voiture->setCoverImage($ancienneImage);
                } else {
                    throw new \Exception('Aucune image fournie.');
                }
            }
            
            // Enregistrer les autres images associées, s'il y en a
            foreach ($voiture->getImages() as $image) {
                $image->setVoiture($voiture);
                $manager->persist($image);
            }
            
            $manager->persist($voiture);
            $manager->flush();
            
            $this->addFlash('success', "L'annonce a bien été modifiée");
            return $this->redirectToRoute('voiture_show', ['id' => $voiture->getId()]);
        }
        
        return $this->render("voiture/edit.html.twig", [
            'myForm' => $form->createView(),
            'voiture' => $voiture
        ]);
    }
    
    // Fonction pour supprimer l'ancienne image du dossier uploads
    private function removeImage($imageFilename): void
    {
        // Construit le chemin complet du fichier
        $imagePath = $this->getParameter('uploads_directory') . '/' . $imageFilename;
        
        // Vérifie si le fichier existe avant de le supprimer
        if (file_exists($imagePath)) {
            unlink($imagePath); // Supprime l'image de uploads
        }
    }   
}