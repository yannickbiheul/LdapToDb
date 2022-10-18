<?php

namespace App\Model;

class Pole
{
    private $nom;

    public function __construct($nom) {
        $this->setNom($nom);
    }

    public function getNom() {
        return $this->nom;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function __toString() {
        return $this->getNom();
    }
}