<?php

namespace App\Entity;

use App\Repository\PersonneRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonneRepository::class)]
class Personne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $telephone_court = null;

    #[ORM\ManyToOne(inversedBy: 'personnes')]
    private ?Metier $metier = null;

    #[ORM\ManyToOne(inversedBy: 'personnes')]
    private ?Hopital $hopital = null;

    #[ORM\ManyToOne(inversedBy: 'personnes')]
    private ?Pole $pole = null;

    #[ORM\ManyToOne(inversedBy: 'personnes')]
    private ?Batiment $batiment = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone_long = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mail = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
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

    public function setTelephoneCourt(string $telephone_court): self
    {
        $this->telephone_court = $telephone_court;

        return $this;
    }

    public function getMetier(): ?Metier
    {
        return $this->metier;
    }

    public function setMetier(?Metier $metier): self
    {
        $this->metier = $metier;

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

    public function getTelephoneLong(): ?string
    {
        return $this->telephone_long;
    }

    public function setTelephoneLong(?string $telephone_long): self
    {
        $this->telephone_long = $telephone_long;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }
}
