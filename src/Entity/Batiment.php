<?php

namespace App\Entity;

use App\Repository\BatimentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BatimentRepository::class)]
class Batiment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'batiment', targetEntity: Pole::class)]
    private Collection $poles;

    #[ORM\ManyToOne(inversedBy: 'batiments')]
    private ?Hopital $hopital = null;

    #[ORM\OneToMany(mappedBy: 'batiment', targetEntity: Personne::class)]
    private Collection $personnes;

    #[ORM\OneToMany(mappedBy: 'batiment', targetEntity: Service::class)]
    private Collection $services;

    public function __construct()
    {
        $this->poles = new ArrayCollection();
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
     * @return Collection<int, Pole>
     */
    public function getPoles(): Collection
    {
        return $this->poles;
    }

    public function addPole(Pole $pole): self
    {
        if (!$this->poles->contains($pole)) {
            $this->poles->add($pole);
            $pole->setBatiment($this);
        }

        return $this;
    }

    public function removePole(Pole $pole): self
    {
        if ($this->poles->removeElement($pole)) {
            // set the owning side to null (unless already changed)
            if ($pole->getBatiment() === $this) {
                $pole->setBatiment(null);
            }
        }

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
            $personne->setBatiment($this);
        }

        return $this;
    }

    public function removePersonne(Personne $personne): self
    {
        if ($this->personnes->removeElement($personne)) {
            // set the owning side to null (unless already changed)
            if ($personne->getBatiment() === $this) {
                $personne->setBatiment(null);
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
            $service->setBatiment($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getBatiment() === $this) {
                $service->setBatiment(null);
            }
        }

        return $this;
    }
}
