<?php

namespace App\Entity;

use App\Repository\VehiculeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehiculeRepository::class)]
class Vehicule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['vehicule:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    #[Groups(['vehicule:read'])]
    private ?string $marque = null;

    #[ORM\Column(length: 200)]
    #[Groups(['vehicule:read'])]
    private ?string $modele = null;

    #[ORM\Column]
    #[Groups(['vehicule:read'])]
    private ?int $nb_porte = null;

    #[ORM\Column(length: 100)]
    #[Groups(['vehicule:read'])]
    private ?string $couleur = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['vehicule:read'])]
    private ?string $photo_presentation = null;

    #[ORM\Column]
    #[Groups(['vehicule:read'])]
    private ?int $kilometrage = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['vehicule:read'])]
    private ?string $presentation_vehicule = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['vehicule:read'])]
    private ?string $remarque = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['vehicule:read'])]
    private ?array $photo_vehicule = null;

    /**
     * @var Collection<int, Tarification>
     */
    #[ORM\OneToMany(targetEntity: Tarification::class, mappedBy: 'vehicule', orphanRemoval: true)]
    private Collection $tarifications;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'vehicule')]
    private Collection $reservations;

    #[ORM\ManyToOne(inversedBy: 'vehicules')]
    private ?Agence $agence = null;

    #[ORM\Column(length: 255)]
    #[Groups(['vehicule:read'])]
    private ?string $type_vehicule = null;

    #[ORM\Column]
    #[Groups(['vehicule:read'])]
    private ?bool $isUtilitaire = null;

    public function __construct()
    {
        $this->tarifications = new ArrayCollection();
        $this->reservations = new ArrayCollection();
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

    public function getNbPorte(): ?int
    {
        return $this->nb_porte;
    }

    public function setNbPorte(int $nb_porte): static
    {
        $this->nb_porte = $nb_porte;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): static
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getPhotoPresentation(): ?string
    {
        return $this->photo_presentation;
    }

    public function setPhotoPresentation(?string $photo_presentation): static
    {
        $this->photo_presentation = $photo_presentation;

        return $this;
    }

    public function getKilometrage(): ?int
    {
        return $this->kilometrage;
    }

    public function setKilometrage(int $kilometrage): static
    {
        $this->kilometrage = $kilometrage;

        return $this;
    }

    public function getPresentationVehicule(): ?string
    {
        return $this->presentation_vehicule;
    }

    public function setPresentationVehicule(string $presentation_vehicule): static
    {
        $this->presentation_vehicule = $presentation_vehicule;

        return $this;
    }

    public function getRemarque(): ?string
    {
        return $this->remarque;
    }

    public function setRemarque(?string $remarque): static
    {
        $this->remarque = $remarque;

        return $this;
    }

    public function getPhotoVehicule(): ?array
    {
        return $this->photo_vehicule;
    }

    public function setPhotoVehicule(?array $photo_vehicule): static
    {
        $this->photo_vehicule = $photo_vehicule;

        return $this;
    }

    /**
     * @return Collection<int, Tarification>
     */
    public function getTarifications(): Collection
    {
        return $this->tarifications;
    }

    public function addTarification(Tarification $tarification): static
    {
        if (!$this->tarifications->contains($tarification)) {
            $this->tarifications->add($tarification);
            $tarification->setVehicule($this);
        }

        return $this;
    }

    public function removeTarification(Tarification $tarification): static
    {
        if ($this->tarifications->removeElement($tarification)) {
            // set the owning side to null (unless already changed)
            if ($tarification->getVehicule() === $this) {
                $tarification->setVehicule(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setVehicule($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getVehicule() === $this) {
                $reservation->setVehicule(null);
            }
        }

        return $this;
    }

    public function getAgence(): ?Agence
    {
        return $this->agence;
    }

    public function setAgence(?Agence $agence): static
    {
        $this->agence = $agence;

        return $this;
    }

    public function getTypeVehicule(): ?string
    {
        return $this->type_vehicule;
    }

    public function setTypeVehicule(string $type_vehicule): static
    {
        $this->type_vehicule = $type_vehicule;

        return $this;
    }

    public function isUtilitaire(): ?bool
    {
        return $this->isUtilitaire;
    }

    public function setIsUtilitaire(bool $isUtilitaire): static
    {
        $this->isUtilitaire = $isUtilitaire;

        return $this;
    }
}
