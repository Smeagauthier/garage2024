<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\VoitureRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: VoitureRepository::class)]
class Voiture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\Column(length: 255)]
    #[Assert\Length(min:0, max:255, minMessage:"La marque doit faire plus de 0 caractères", maxMessage:"La marque ne doit pas faire plus de 255 caractères")]
    private ?string $marque = null;
    
    #[ORM\Column(length: 255)]
    #[Assert\Length(min:0, max:255, minMessage:"Le modèle doit faire plus de 0 caractère", maxMessage:"Le modèle ne doit pas faire plus de 255 caractères")]
    private ?string $modele = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $coverImage = null;
    
    #[ORM\Column]
    #[Assert\NotBlank(message: "Le champs kilomètrage ne peut pas être vide")]
    private ?int $km = null;
    
    #[ORM\Column]
    #[Assert\NotBlank(message: "Le champs prix ne peut pas être vide")]
    private ?float $prix = null;
    
    #[ORM\Column]
    #[Assert\NotBlank(message: "Le champs nombre de propriétaire ne peut pas être vide")]
    private ?int $nbProprietaire = null;
    
    #[ORM\Column]
    #[Assert\NotBlank(message: "Le champs cylindrée ne peut pas être vide")]
    private ?int $cylindree = null;
    
    #[ORM\Column]
    #[Assert\NotBlank(message: "Le champs puissance ne peut pas être vide")]
    private ?int $puissance = null;
    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le champs carburant ne peut pas être vide")]
    private ?string $carburant = null;
    
    #[ORM\Column]
    #[Assert\NotBlank(message: "Le champs année de mise en circulation ne peut pas être vide")]
    private ?int $annee = null;
    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le champs transmission ne peut pas être vide")]
    private ?string $transmission = null;
    
    #[ORM\Column(length: 255)]
    #[Assert\Length(min:10, max:255, minMessage:"La description ne doit pas faire moins que 20 caractères", maxMessage:"La description ne doit pas faire plus de 255 caractères")]
    private ?string $description = null;
    
    #[ORM\Column(length: 255)]
    #[Assert\Length(min:10, max:255, minMessage:"Le champs autres options ne doit pas faire moins que 10 caractères", maxMessage:"Le champs autres options ne doit pas faire plus de 255 caractères")]
    private ?string $autresOptions = null;
    
    /**
    * @var Collection<int, Image>
    */
    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'voiture', orphanRemoval: true)]
    #[Assert\Valid()]
    private Collection $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }
    
    
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

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(?string $coverImage): self
    {
        $this->coverImage = $coverImage ; 
        return $this;
    }
    
    public function getImages(): Collection
    {
        return $this->images;
    }
    
    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setVoiture($this); 
        }
        
        return $this;
    }
    
    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            // Set the owning side to null (unless already changed)
            if ($image->getVoiture() === $this) {
                $image->setVoiture(null);
            }
        }
        
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
    
    public function isCylindree(): ?int
    {
        return $this->cylindree;
    }
    
    public function setCylindree(int $cylindree): static
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
    
}