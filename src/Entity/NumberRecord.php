<?php

namespace App\Entity;

use App\Repository\NumberRecordRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NumberRecordRepository::class)]
class NumberRecord
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $didNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $private = null;

    #[ORM\OneToMany(mappedBy: 'numberRecord', targetEntity: PeopleRecord::class)]
    private Collection $peopleRecords;

    public function __construct()
    {
        $this->peopleRecords = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getDidNumber(): ?string
    {
        return $this->didNumber;
    }

    public function setDidNumber(?string $didNumber): self
    {
        $this->didNumber = $didNumber;

        return $this;
    }

    public function getPrivate(): ?string
    {
        return $this->private;
    }

    public function setPrivate(string $private): self
    {
        $this->private = $private;

        return $this;
    }

    /**
     * @return Collection<int, PeopleRecord>
     */
    public function getPeopleRecords(): Collection
    {
        return $this->peopleRecords;
    }

    public function addPeopleRecord(PeopleRecord $peopleRecord): self
    {
        if (!$this->peopleRecords->contains($peopleRecord)) {
            $this->peopleRecords->add($peopleRecord);
            $peopleRecord->setNumberRecord($this);
        }

        return $this;
    }

    public function removePeopleRecord(PeopleRecord $peopleRecord): self
    {
        if ($this->peopleRecords->removeElement($peopleRecord)) {
            // set the owning side to null (unless already changed)
            if ($peopleRecord->getNumberRecord() === $this) {
                $peopleRecord->setNumberRecord(null);
            }
        }

        return $this;
    }
}
