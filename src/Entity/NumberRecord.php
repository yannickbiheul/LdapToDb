<?php

namespace App\Entity;

use App\Repository\NumberRecordRepository;
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

    public function getId(): ?int
    {
        return $this->id;
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
}
