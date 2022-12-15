<?php

namespace App\Entity;

use App\Repository\PeopleRecordRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PeopleRecordRepository::class)]
class PeopleRecord
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $sn = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $displayGn = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mainLineNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $didNumbers = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mail = null;

    #[ORM\Column(length: 255)]
    private ?string $hierarchySV = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $attr1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $attr5 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $attr6 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $attr7 = null;

    #[ORM\Column(length: 255)]
    private ?string $cleUid = null;

    #[ORM\ManyToOne(inversedBy: 'peopleRecords')]
    #[ORM\JoinColumn(nullable: false)]
    private ?NumberRecord $numberRecord = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSn(): ?string
    {
        return $this->sn;
    }

    public function setSn(string $sn): self
    {
        $this->sn = $sn;

        return $this;
    }

    public function getDisplayGn(): ?string
    {
        return $this->displayGn;
    }

    public function setDisplayGn(?string $displayGn): self
    {
        $this->displayGn = $displayGn;

        return $this;
    }

    public function getMainLineNumber(): ?string
    {
        return $this->mainLineNumber;
    }

    public function setMainLineNumber(?string $mainLineNumber): self
    {
        $this->mainLineNumber = $mainLineNumber;

        return $this;
    }

    public function getDidNumbers(): ?string
    {
        return $this->didNumbers;
    }

    public function setDidNumbers(?string $didNumbers): self
    {
        $this->didNumbers = $didNumbers;

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

    public function getHierarchySV(): ?string
    {
        return $this->hierarchySV;
    }

    public function setHierarchySV(string $hierarchySV): self
    {
        $this->hierarchySV = $hierarchySV;

        return $this;
    }

    public function getAttr1(): ?string
    {
        return $this->attr1;
    }

    public function setAttr1(?string $attr1): self
    {
        $this->attr1 = $attr1;

        return $this;
    }

    public function getAttr5(): ?string
    {
        return $this->attr5;
    }

    public function setAttr5(?string $attr5): self
    {
        $this->attr5 = $attr5;

        return $this;
    }

    public function getAttr6(): ?string
    {
        return $this->attr6;
    }

    public function setAttr6(?string $attr6): self
    {
        $this->attr6 = $attr6;

        return $this;
    }

    public function getAttr7(): ?string
    {
        return $this->attr7;
    }

    public function setAttr7(?string $attr7): self
    {
        $this->attr7 = $attr7;

        return $this;
    }

    public function getCleUid(): ?string
    {
        return $this->cleUid;
    }

    public function setCleUid(string $cleUid): self
    {
        $this->cleUid = $cleUid;

        return $this;
    }

    public function getNumberRecord(): ?NumberRecord
    {
        return $this->numberRecord;
    }

    public function setNumberRecord(?NumberRecord $numberRecord): self
    {
        $this->numberRecord = $numberRecord;

        return $this;
    }
}
