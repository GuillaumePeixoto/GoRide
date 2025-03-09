<?php

namespace App\Entity;

use App\Repository\VehiculeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehiculeRepository::class)]
class Vehicule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    private ?string $marque = null;

    #[ORM\Column(length: 200)]
    private ?string $modele = null;

    #[ORM\Column]
    private ?int $nb_porte = null;

    #[ORM\Column(length: 100)]
    private ?string $couleur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo_presentation = null;

    #[ORM\Column]
    private ?int $kilometrage = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $presentation_vehicule = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $remarque = null;

    #[ORM\Column(nullable: true)]
    private ?array $photo_vehicule = null;

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
}
