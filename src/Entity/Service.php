<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ServiceRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getServices"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getServices"])]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getServices"])]
    private ?string $telephone_court = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getServices"])]
    private ?string $telephone_long = null;

    #[ORM\ManyToOne(inversedBy: 'services')]
    #[Groups(["getServices"])]
    private ?Pole $pole = null;

    #[ORM\ManyToOne(inversedBy: 'services')]
    #[Groups(["getServices"])]
    private ?Batiment $batiment = null;

    #[ORM\ManyToOne(inversedBy: 'services')]
    #[Groups(["getServices"])]
    private ?Hopital $hopital = null;

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

    public function getHopital(): ?Hopital
    {
        return $this->hopital;
    }

    public function setHopital(?Hopital $hopital): self
    {
        $this->hopital = $hopital;

        return $this;
    }
}
