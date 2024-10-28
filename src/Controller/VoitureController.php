<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
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
}
