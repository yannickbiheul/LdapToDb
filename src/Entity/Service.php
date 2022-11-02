<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone_court = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone_long = null;

    #[ORM\ManyToOne(inversedBy: 'services')]
    private ?Pole $pole = null;

    #[ORM\ManyToOne(inversedBy: 'services')]
    private ?Batiment $batiment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTelephoneCourt(): ?string
    {
        return $this->telephone_court;
    }

    public function setTelephoneCourt(?string $telephone_court): self
    {
        $this->telephone_court = $telephone_court;

        return $this;
    }

    public function getTelephoneLong(): ?string
    {
        return $this->telephone_long;
    }

    public function setTelephoneLong(?string $telephone_long): self
    {
        $this->telephone_long = $telephone_long;

        return $this;
    }

    public function getPole(): ?Pole
    {
        return $this->pole;
    }

    public function setPole(?Pole $pole): self
    {
        $this->pole = $pole;

        return $this;
    }

    public function getBatiment(): ?Batiment
    {
        return $this->batiment;
    }

    public function setBatiment(?Batiment $batiment): self
    {
        $this->batiment = $batiment;

        return $this;
    }
}
