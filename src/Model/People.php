<?php

namespace App\Model;

class People
{
    protected $prenom;
    protected $nom;
    protected $numeroCourt;
    protected $numeroLong;
    protected $mail;
    protected $pole;
    protected $metier;
    protected $poste;

    public function __construct($prenom, $nom, $numeroCourt, $numeroLong, $mail, $pole, $metier, $poste) {
        $this->prenom = $prenom;
        $this->nom = $nom;
        $this->numeroCourt = $numeroCourt;
        $this->numeroLong = $numeroLong;
        $this->mail = $mail;
        $this->pole = $pole;
        $this->metier = $metier;
        $this->poste = $poste;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    public function getNom() {
        return $this->nom;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function getNumeroCourt() {
        return $this->numeroCourt;
    }

    public function setNumeroCourt($numeroCourt) {
        $this->numeroCourt = $numeroCourt;
    }

    public function getNumeroLong() {
        return $this->numeroLong;
    }

    public function setNumeroLong($numeroLong) {
        $this->numeroLong = $numeroLong;
    }

    public function getMail() {
        return $this->mail;
    }

    public function setMail($mail) {
        $this->mail = $mail;
    }

    public function getPole() {
        return $this->pole;
    }

    public function setPole($pole) {
        $this->pole = $pole;
    }

    public function getMetier() {
        return $this->metier;
    }

    public function setMetier($metier) {
        $this->metier = $metier;
    }

    public function getPoste() {
        return $this->poste;
    }

    public function setPoste($poste) {
        $this->poste = $poste;
    }
}