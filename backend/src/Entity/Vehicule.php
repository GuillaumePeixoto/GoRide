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
    private ?int $nbPorte = null;

    #[ORM\Column(length: 100)]
    #[Groups(['vehicule:read'])]
    private ?string $couleur = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['vehicule:read'])]
    private ?string $photoPresentation = null;

    #[ORM\Column]
    #[Groups(['vehicule:read'])]
    private ?int $kilometrage = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['vehicule:read'])]
    private ?string $presentationVehicule = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['vehicule:read'])]
    private ?string $remarque = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['vehicule:read'])]
    private ?array $photoVehicule = null;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'vehicule')]
    private Collection $reservations;

    #[ORM\ManyToOne(inversedBy: 'vehicules')]
    #[Groups(['vehicule:read'])]
    private ?Agence $agence = null;

    #[ORM\Column(length: 255)]
    #[Groups(['vehicule:read'])]
    private ?string $typeVehicule = null;

    #[ORM\Column]
    #[Groups(['vehicule:read'])]
    private ?bool $isUtilitaire = null;

    #[ORM\Column(length: 100)]
    #[Groups(['vehicule:read'])]
    private ?string $typeCarburant = null;

    #[ORM\Column(length: 100)]
    #[Groups(['vehicule:read'])]
    private ?string $boiteDeVitesse = null;

    #[ORM\Column]
    #[Groups(['vehicule:read'])]
    private ?float $prixJour = null;

    public function __construct()
    {
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
        return $this->nbPorte;
    }

    public function setNbPorte(int $nbPorte): static
    {
        $this->nbPorte = $nbPorte;

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
        return $this->photoPresentation;
    }

    public function setPhotoPresentation(?string $photoPresentation): static
    {
        $this->photoPresentation = $photoPresentation;

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
        return $this->presentationVehicule;
    }

    public function setPresentationVehicule(string $presentationVehicule): static
    {
        $this->presentationVehicule = $presentationVehicule;

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
        return $this->photoVehicule;
    }

    public function setPhotoVehicule(?array $photoVehicule): static
    {
        $this->photoVehicule = $photoVehicule;

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
        return $this->typeVehicule;
    }

    public function setTypeVehicule(string $typeVehicule): static
    {
        $this->typeVehicule = $typeVehicule;

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

    public function getTypeCarburant(): ?string
    {
        return $this->typeCarburant;
    }

    public function setTypeCarburant(string $typeCarburant): static
    {
        $this->typeCarburant = $typeCarburant;

        return $this;
    }

    public function getBoiteDeVitesse(): ?string
    {
        return $this->boiteDeVitesse;
    }

    public function setBoiteDeVitesse(string $boiteDeVitesse): static
    {
        $this->boiteDeVitesse = $boiteDeVitesse;

        return $this;
    }

    public function getPrixJour(): ?float
    {
        return $this->prixJour;
    }

    public function setPrixJour(float $prixJour): static
    {
        $this->prixJour = $prixJour;

        return $this;
    }
}
