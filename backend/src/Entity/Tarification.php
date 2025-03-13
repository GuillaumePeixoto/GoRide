<?php

namespace App\Entity;

use App\Repository\TarificationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TarificationRepository::class)]
class Tarification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tarifications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Vehicule $vehicule = null;

    #[ORM\Column(nullable: true)]
    private ?float $prix_jour = null;

    #[ORM\Column(nullable: true)]
    private ?float $prix_mois = null;

    #[ORM\Column(nullable: true)]
    private ?float $prix_semaine = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVehicule(): ?Vehicule
    {
        return $this->vehicule;
    }

    public function setVehicule(?Vehicule $vehicule): static
    {
        $this->vehicule = $vehicule;

        return $this;
    }

    public function getPrixJour(): ?float
    {
        return $this->prix_jour;
    }

    public function setPrixJour(?float $prix_jour): static
    {
        $this->prix_jour = $prix_jour;

        return $this;
    }

    public function getPrixMois(): ?float
    {
        return $this->prix_mois;
    }

    public function setPrixMois(?float $prix_mois): static
    {
        $this->prix_mois = $prix_mois;

        return $this;
    }

    public function getPrixSemaine(): ?float
    {
        return $this->prix_semaine;
    }

    public function setPrixSemaine(?float $prix_semaine): static
    {
        $this->prix_semaine = $prix_semaine;

        return $this;
    }
}
