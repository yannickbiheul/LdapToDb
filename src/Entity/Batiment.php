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

    public function __construct()
    {
        $this->poles = new ArrayCollection();
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
}
