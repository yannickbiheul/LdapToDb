<?php

namespace App\Service;

use App\Entity\Hopital;
use App\Repository\HopitalRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\PeopleRecordRepository;

class HopitalManager
{
    private $doctrine;
    private $peopleRecordRepo;
    private $hopitalRepo;

    /**
     * Constructeur
     * Injection de ConnectLdapService
     */
    public function __construct(ManagerRegistry $doctrine,
                                PeopleRecordRepository $peopleRecordRepo,
                                HopitalRepository $hopitalRepo) {
        $this->doctrine = $doctrine;
        $this->peopleRecordRepo = $peopleRecordRepo;
        $this->hopitalRepo = $hopitalRepo;
    }

    /**
     * Récupérer la liste des hopitaux 
     * Retourne tableau de string
     */
    public function getHopitaux() {
        $hopitaux = array();
        $listPeople = $this->peopleRecordRepo->findAll();

        for ($i=0; $i < count($listPeople); $i++) { 
            if (property_exists($listPeople[$i], 'attr5') && $listPeople[$i]->getAttr5() != null) {
                array_push($hopitaux, $listPeople[$i]->getAttr5());
            }
        }

        return array_unique($hopitaux);
    }

    public function corrigerHopitaux() {
        $listHopitaux = $this->getHopitaux();
        $listeCorrect = array();

        foreach ($listHopitaux as $key => $value) {
            if ($value == "CHIC QUIMPER" || $value == "LAENNEC" || $value == "Laennec" || $value == "Lennec" || $value == "Laenec" || $value == "Fontenoy" || $value == "Quimper") {
                array_push($listeCorrect, "CHIC QUIMPER");
            } elseif ($value == "Concarneau" || $value == "CHIC SITE DE CONCARNEAU" || $value == "CC") {
                array_push($listeCorrect, "CHIC CONCARNEAU");
            } elseif ($value == "RESIDENCE KER RADENEG" || $value == "Keradennec") {
                array_push($listeCorrect, "RESIDENCE KER RADENEG");
            } elseif ($value == "Ty Glazic" || $value == "RESIDENCE TY GLAZK") {
                array_push($listeCorrect, "RESIDENCE TY GLAZIG");
            } elseif ($value == "Ty Creach" || $value == "RESIDENCE TY CREACH") {
                array_push($listeCorrect, "RESIDENCE TY CREACH");
            } else {
                array_push($listeCorrect, $value);
            }
        }

        return array_unique($listeCorrect);
    }

    /**
     * Enregistrer les hopitaux dans la bdd
     * 
     */
    public function enregistrerHopitaux() {
        // Récupérer liste hopitaux et manager
        $entityManager = $this->doctrine->getManager();
        $listHopitaux = $this->corrigerHopitaux();

        // parcourir la liste des hôpitaux
        foreach ($listHopitaux as $key => $value) {
            // Créer un objet Hopital
            $hopital = new Hopital();
            // Attribuer le nom
            $hopital->setNom($value);
            
            if ($this->hopitalRepo->findOneBy(['nom' => $hopital->getNom()]) == null) {
                $entityManager->persist($hopital);
            }
        }

        $entityManager->flush();
    }

    public function voirHopitaux() {
        return $this->hopitalRepo->findAll();
    }

}