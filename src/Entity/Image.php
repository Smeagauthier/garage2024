<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    /**
     * @var Collection<int, Voiture>
     */
    #[ORM\OneToMany(targetEntity: Voiture::class, mappedBy: 'imagesID')]
    private Collection $voiture_image;

    #[ORM\Column(length: 255)]
    private ?string $caption = null;

    public function __construct()
    {
        $this->voiture_image = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Collection<int, Voiture>
     */
    public function getVoitureImage(): Collection
    {
        return $this->voiture_image;
    }

    public function addVoitureImage(Voiture $voitureImage): static
    {
        if (!$this->voiture_image->contains($voitureImage)) {
            $this->voiture_image->add($voitureImage);
            $voitureImage->setImagesID($this);
        }

        return $this;
    }

    public function removeVoitureImage(Voiture $voitureImage): static
    {
        if ($this->voiture_image->removeElement($voitureImage)) {
            // set the owning side to null (unless already changed)
            if ($voitureImage->getImagesID() === $this) {
                $voitureImage->setImagesID(null);
            }
        }

        return $this;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setCaption(string $caption): static
    {
        $this->caption = $caption;

        return $this;
    }
}
