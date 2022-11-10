<?php

namespace App\Service;

use App\Entity\Entree;
use App\Entity\Metier;
use App\Entity\Personne;
use App\Entity\PeopleRecord;
use App\Repository\PoleRepository;
use App\Service\ConnectLdapService;
use App\Repository\EntreeRepository;
use App\Repository\MetierRepository;
use App\Repository\HopitalRepository;
use App\Repository\ServiceRepository;
use App\Repository\BatimentRepository;
use App\Repository\NumberRecordRepository;
use App\Repository\PersonneRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\PeopleRecordRepository;

/**
 * Contrainte n°1 : Ne pas afficher les lignes rouges
 * Contrainte n°2 : Ne pas afficher les numéros de chambres
 */
class PersonneManager
{
    private $metierRepo;
    private $doctrine;
    private $personneRepo;
    private $hopitalRepo;
    private $poleRepo;
    private $batimentRepo;
    private $serviceRepo;
    private $peopleRecordRepo;
    private $numberRecordRepo;

    /**
     * Constructeur
     * Injection de ConnectLdapService
     */
    public function __construct(MetierRepository $metierRepo,
                                ManagerRegistry $doctrine,
                                PersonneRepository $personneRepo,
                                HopitalRepository $hopitalRepo,
                                PoleRepository $poleRepo,
                                BatimentRepository $batimentRepo,
                                ServiceRepository $serviceRepo,
                                PeopleRecordRepository $peopleRecordRepo,
                                NumberRecordRepository $numberRecordRepo) {
        $this->metierRepo = $metierRepo;
        $this->doctrine = $doctrine;
        $this->personneRepo = $personneRepo;
        $this->hopitalRepo = $hopitalRepo;
        $this->poleRepo = $poleRepo;
        $this->batimentRepo = $batimentRepo;
        $this->serviceRepo = $serviceRepo;
        $this->peopleRecordRepo = $peopleRecordRepo;
        $this->numberRecordRepo = $numberRecordRepo;
    }

    /**
     * Récupérer la liste des personnes 
     * Retourne tableau de string
     */
    public function getPersonnes() {
        $personnes = array();
        $listPeople = $this->peopleRecordRepo->findAll();

        for ($i=0; $i < count($listPeople); $i++) { 
            
            if ($listPeople[$i]->getSn() != null && $listPeople[$i]->getDisplayGn() != null) {
                $tableau = array();
                $champPrivate = $this->numberRecordRepo->findOneBy(['phoneNumber' => $listPeople[$i]->getMainLineNumber()]);
                if ($champPrivate) {
                    $private = $this->numberRecordRepo->findOneBy(['phoneNumber' => $listPeople[$i]->getMainLineNumber()])->getPrivate();
                } else {
                    $private = null;
                }

                $tableau = ["Nom" => $listPeople[$i]->getSn(),
                              "Prenom" => $listPeople[$i]->getDisplayGn(),
                              "Clé_UID" => $listPeople[$i]->getCleUid(),
                              "Telephone_Court" => $listPeople[$i]->getMainLineNumber(),
                              "Telephone_Long" => $listPeople[$i]->getDidNumbers(),
                              "Mail" => $listPeople[$i]->getMail(),
                              "Pole" => $listPeople[$i]->getAttr1(),
                              "Hopital" => $listPeople[$i]->getAttr5(),
                              "Metier" => $listPeople[$i]->getAttr7(),
                              "Batiment" => $listPeople[$i]->getAttr6(),
                              "Hierarchie" => $listPeople[$i]->getHierarchySV(),
                              "Private" => $private];
                
                array_push($personnes, $tableau);
            }
        }

        return $personnes;
    }

    /**
     * Enregistrer toutes les personnes
     */
    public function enregistrerPersonnes() {
        $entityManager = $this->doctrine->getManager();
        $listPersonnes = $this->getPersonnes();

        for ($i=0; $i < count($listPersonnes); $i++) { 
            
            // CONTRAINTES: numéros de chambres et liste rouge et personne non présente dans la base
            $personnePresente = $this->personneRepo->findOneBy(['nom' => strtoupper($listPersonnes[$i]["Nom"]), 'prenom' => $listPersonnes[$i]["Prenom"]]);
            if ($listPersonnes[$i]["Hierarchie"] != "PATIENTS/CHIC" && $listPersonnes[$i]["Private"] != "LR" && $personnePresente == null) {
                $personne = new Personne();
                // BATIMENT
                $batiment = $this->batimentRepo->findOneBy(['nom' => $listPersonnes[$i]["Batiment"]]);
                $personne->setBatiment($batiment);
                // HOPITAL
                $hopital = $this->hopitalRepo->findOneBy(['nom' => $listPersonnes[$i]["Hopital"]]);
                $personne->setHopital($hopital);
                // MAIL
                $personne->setMail($listPersonnes[$i]["Mail"]);
                // METIER
                $metier = $this->metierRepo->findOneBy(['nom' => $listPersonnes[$i]["Metier"]]);
                $personne->setMetier($metier);
                // NOM
                $personne->setNom(strtoupper($listPersonnes[$i]["Nom"]));
                // POLE
                $pole = $this->poleRepo->findOneBy(['nom' => $listPersonnes[$i]["Pole"]]);
                $personne->setPole($pole);
                // PRENOM
                $personne->setPrenom($listPersonnes[$i]["Prenom"]);
                // TELEPHONE COURT
                if ($listPersonnes[$i]["Telephone_Court"]) {
                    $personne->setTelephoneCourt($listPersonnes[$i]["Telephone_Court"]);
                }
                // TELEPHONE LONG
                $personne->setTelephoneLong($listPersonnes[$i]["Telephone_Long"]);

                $entityManager->persist($personne);
            }
        }

        $entityManager->flush();
    }

}