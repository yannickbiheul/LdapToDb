<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\HopitalRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: HopitalRepository::class)]
class Hopital
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getHopitaux", "getBatiments", "getPoles"])]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(["getHopitaux", "getBatiments", "getPoles"])]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'hopital', targetEntity: Batiment::class)]
    #[Groups(["getHopitaux"])]
    private Collection $batiments;

    #[ORM\OneToMany(mappedBy: 'hopital', targetEntity: Personne::class)]
    private Collection $personnes;

    #[ORM\OneToMany(mappedBy: 'hopital', targetEntity: Service::class)]
    private Collection $services;

    public function __construct()
    {
        $this->batiments = new ArrayCollection();
        $this->personnes = new ArrayCollection();
        $this->services = new ArrayCollection();
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
            $batiment->setHopital($this);
        }

        return $this;
    }

    public function removeBatiment(Batiment $batiment): self
    {
        if ($this->batiments->removeElement($batiment)) {
            // set the owning side to null (unless already changed)
            if ($batiment->getHopital() === $this) {
                $batiment->setHopital(null);
            }
        }

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
            $personne->setHopital($this);
        }

        return $this;
    }

    public function removePersonne(Personne $personne): self
    {
        if ($this->personnes->removeElement($personne)) {
            // set the owning side to null (unless already changed)
            if ($personne->getHopital() === $this) {
                $personne->setHopital(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Service>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
            $service->setHopital($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getHopital() === $this) {
                $service->setHopital(null);
            }
        }

        return $this;
    }
}
