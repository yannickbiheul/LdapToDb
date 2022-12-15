<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MetierRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MetierRepository::class)]
class Metier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getMetiers", "getPersonnes"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getMetiers", "getPersonnes"])]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'metier', targetEntity: Personne::class)]
    private Collection $personnes;

    #[ORM\ManyToMany(targetEntity: Batiment::class, inversedBy: 'metiers')]
    private Collection $batiments;

    public function __construct()
    {
        $this->personnes = new ArrayCollection();
        $this->batiments = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Personne>
     */
    public function getPersonnes(): Collection
    {
        return $this->personnes;
    }

    public function addPersonne(Personne $personne): self
    {
        if (!$this->personnes->contains($personne)) {
            $this->personnes->add($personne);
            $personne->setMetier($this);
        }

        return $this;
    }

    public function removePersonne(Personne $personne): self
    {
        if ($this->personnes->removeElement($personne)) {
            // set the owning side to null (unless already changed)
            if ($personne->getMetier() === $this) {
                $personne->setMetier(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Batiment>
     */
    public function getBatiments(): Collection
    {
        return $this->batiments;
    }

    public function addBatiment(Batiment $batiment): self
    {
        if (!$this->batiments->contains($batiment)) {
            $this->batiments->add($batiment);
        }

        return $this;
    }

    public function removeBatiment(Batiment $batiment): self
    {
        $this->batiments->removeElement($batiment);

        return $this;
    }
}
