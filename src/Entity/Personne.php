<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PersonneRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PersonneRepository::class)]
class Personne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getPersonnes"])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getPersonnes"])]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getPersonnes"])]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getPersonnes"])]
    private ?string $tel_court = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getPersonnes"])]
    private ?string $metier = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getPersonnes"])]
    private ?string $hopital = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getPersonnes"])]
    private ?string $pole = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getPersonnes"])]
    private ?string $batiment = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getPersonnes"])]
    private ?string $tel_long = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getPersonnes"])]
    private ?string $mail = null;

    #[ORM\Column(length: 255)]
    private ?string $cle_uid = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
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

    public function getTelCourt(): ?string
    {
        return $this->tel_court;
    }

    public function setTelCourt(?string $tel_court): self
    {
        $this->tel_court = $tel_court;

        return $this;
    }

    public function getTelLong(): ?string
    {
        return $this->tel_long;
    }

    public function setTelLong(?string $tel_long): self
    {
        $this->tel_long = $tel_long;

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

    public function getMetier(): ?string
    {
        return $this->metier;
    }

    public function setMetier(?string $metier): self
    {
        $this->metier = $metier;

        return $this;
    }

    public function getHopital(): ?string
    {
        return $this->hopital;
    }

    public function setHopital(?string $hopital): self
    {
        $this->hopital = $hopital;

        return $this;
    }

    public function getPole(): ?string
    {
        return $this->pole;
    }

    public function setPole(?string $pole): self
    {
        $this->pole = $pole;

        return $this;
    }

    public function getBatiment(): ?string
    {
        return $this->batiment;
    }

    public function setBatiment(?string $batiment): self
    {
        $this->batiment = $batiment;

        return $this;
    }

    public function getCleUid(): ?string
    {
        return $this->cle_uid;
    }

    public function setCleUid(string $cle_uid): self
    {
        $this->cle_uid = $cle_uid;

        return $this;
    }
}
