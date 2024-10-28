<?php

namespace App\Entity;

use App\Repository\VoitureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VoitureRepository::class)]
class Voiture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $marque = null;

    #[ORM\Column(length: 255)]
    private ?string $modele = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column]
    private ?int $km = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column]
    private ?int $nbProprietaire = null;

    #[ORM\Column]
    private ?bool $cylindree = null;

    #[ORM\Column]
    private ?int $puissance = null;

    #[ORM\Column(length: 255)]
    private ?string $carburant = null;

    #[ORM\Column]
    private ?int $annee = null;

    #[ORM\Column(length: 255)]
    private ?string $transmission = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $autresOptions = null;

    #[ORM\ManyToOne(inversedBy: 'voiture_image')]
    private ?Image $imagesID = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): static
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): static
    {
        $this->modele = $modele;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getKm(): ?int
    {
        return $this->km;
    }

    public function setKm(int $km): static
    {
        $this->km = $km;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getNbProprietaire(): ?int
    {
        return $this->nbProprietaire;
    }

    public function setNbProprietaire(int $nbProprietaire): static
    {
        $this->nbProprietaire = $nbProprietaire;

        return $this;
    }

    public function isCylindree(): ?bool
    {
        return $this->cylindree;
    }

    public function setCylindree(bool $cylindree): static
    {
        $this->cylindree = $cylindree;

        return $this;
    }

    public function getPuissance(): ?int
    {
        return $this->puissance;
    }

    public function setPuissance(int $puissance): static
    {
        $this->puissance = $puissance;

        return $this;
    }

    public function getCarburant(): ?string
    {
        return $this->carburant;
    }

    public function setCarburant(string $carburant): static
    {
        $this->carburant = $carburant;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): static
    {
        $this->annee = $annee;

        return $this;
    }

    public function getTransmission(): ?string
    {
        return $this->transmission;
    }

    public function setTransmission(string $transmission): static
    {
        $this->transmission = $transmission;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getAutresOptions(): ?string
    {
        return $this->autresOptions;
    }

    public function setAutresOptions(string $autresOptions): static
    {
        $this->autresOptions = $autresOptions;

        return $this;
    }

    public function getImagesID(): ?Image
    {
        return $this->imagesID;
    }

    public function setImagesID(?Image $imagesID): static
    {
        $this->imagesID = $imagesID;

        return $this;
    }
}
