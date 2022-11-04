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
    private $ldap;
    private $connectLdapService;
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
    public function __construct(ConnectLdapService $connectLdapService,
                                MetierRepository $metierRepo,
                                ManagerRegistry $doctrine,
                                PersonneRepository $personneRepo,
                                HopitalRepository $hopitalRepo,
                                PoleRepository $poleRepo,
                                BatimentRepository $batimentRepo,
                                ServiceRepository $serviceRepo,
                                PeopleRecordRepository $peopleRecordRepo,
                                NumberRecordRepository $numberRecordRepo) {
        $this->connectLdapService = $connectLdapService;
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
                array_push($tableau, $listPeople[$i]->getSn());
                array_push($tableau, $listPeople[$i]->getDisplayGn());
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
        $listPeopleRecord = $this->peopleRecordRepo->findAll();
        $listNumberRecord = $this->numberRecordRepo->findAll();

        for ($i=0; $i < count($listPeopleRecord); $i++) { 

            // CONTRAINTES: numéros de chambres et liste rouge
            $private = $this->numberRecordRepo->findOneBy(['phoneNumber' => $listPeopleRecord[$i]->getMainLineNumber()]);
            if ($listPeopleRecord[$i]->getHierarchySV() != "PATIENTS/CHIC" && $private->getPrivate() != "LR") {

                // CREATION DE L'OBJET
                $personne = new Personne();
                // PRENOM
                $personne->setPrenom($listPeopleRecord[$i]->getDisplayGn());
                // NOM
                $personne->setNom($listPeopleRecord[$i]->getSn());
                // TELEPHONE COURT
                $personne->setTelephoneCourt($listPeopleRecord[$i]->getMainLineNumber());


            }
        }
    }

    /**
     * Trouver le métier de la personne : 
     * Retourne Metier
     */
    public function findMetier($nomPersonne, $prenomPersonne) {
        // Connexion au Ldap
        $ldap = $this->connectLdapService->connexionLdap();
        // Création d'un filtre de requête
        $filter = '(&(sn='.$nomPersonne.')(displayGn='.$prenomPersonne.'))';
        // Tableau des attributs demandés
        $justThese = array('attr7');
        // Envoi de la requête
        $query = ldap_search($ldap, $this->connectLdapService->getBasePeople(), $filter, $justThese);
        // Récupération des réponses de la requête
        $metier = ldap_get_entries($ldap, $query);
        
        // Vérifier que la personne est bien reliée à un métier
        if (in_array('attr7', $metier[0])) {
            $metier = $this->metierRepo->findBy(["nom" => $metier[0]['attr7'][0]]);
            return $metier[0];
        }
        return null;
    }

    /**
     * Trouver l'hôpital de la personne : 
     * Retourne Hopital
     */
    public function findHopital($nomPersonne, $prenomPersonne) {
        // Connexion au Ldap
        $ldap = $this->connectLdapService->connexionLdap();
        // Création d'un filtre de requête
        $filter = '(&(sn='.$nomPersonne.')(displayGn='.$prenomPersonne.'))';
        // Tableau des attributs demandés
        $justThese = array('attr5');
        // Envoi de la requête
        $query = ldap_search($ldap, $this->connectLdapService->getBasePeople(), $filter, $justThese);
        // Récupération des réponses de la requête
        $hopital = ldap_get_entries($ldap, $query);
        
        // Vérifier que la personne est bien reliée à un hôpital
        if (in_array('attr5', $hopital[0])) {
            $hopital = $this->hopitalRepo->findBy(["nom" => $hopital[0]['attr5'][0]]);
            return $hopital[0];
        }
        return null;
    }

    /**
     * Trouver le bâtiment de la personne : 
     * Retourne Batiment
     */
    public function findBatiment($nomPersonne, $prenomPersonne) {
        // Connexion au Ldap
        $ldap = $this->connectLdapService->connexionLdap();
        // Création d'un filtre de requête
        $filter = '(&(sn='.$nomPersonne.')(displayGn='.$prenomPersonne.'))';
        // Tableau des attributs demandés
        $justThese = array('attr6');
        // Envoi de la requête
        $query = ldap_search($ldap, $this->connectLdapService->getBasePeople(), $filter, $justThese);
        // Récupération des réponses de la requête
        $batiment = ldap_get_entries($ldap, $query);
        
        // Vérifier que la personne est bien reliée à un bâtiment
        if (in_array('attr6', $batiment[0])) {
            $batiment = $this->batimentRepo->findBy(["nom" => $batiment[0]['attr6'][0]]);
            return $batiment[0];
        }
        return null;
    }

    /**
     * Trouver le pôle de la personne : 
     * Retourne Pole
     */
    public function findPole($nomPersonne, $prenomPersonne) {
        // Connexion au Ldap
        $ldap = $this->connectLdapService->connexionLdap();
        // Création d'un filtre de requête
        $filter = '(&(sn='.$nomPersonne.')(displayGn='.$prenomPersonne.'))';
        // Tableau des attributs demandés
        $justThese = array('attr1');
        // Envoi de la requête
        $query = ldap_search($ldap, $this->connectLdapService->getBasePeople(), $filter, $justThese);
        // Récupération des réponses de la requête
        $pole = ldap_get_entries($ldap, $query);
        
        // Vérifier que la personne est bien reliée à un pôle
        if (in_array('attr1', $pole[0])) {
            $pole = $this->poleRepo->findBy(["nom" => $pole[0]['attr1'][0]]);
            return $pole[0];
        }
        return null;
    }

    /**
     * Contrainte n°1 : Vérifier que le numéro n'est pas en liste rouge
     * 
     */
    public function isRedList($numero) {
        // Connexion au Ldap
        $ldap = $this->connectLdapService->connexionLdap();
        // Création d'un filtre de requête
        $filter = '(&(objectClass=numberRecord)(phoneNumber=' . $numero .'))';
        // Tableau des attributs demandés
        $justThese = array('mainLineNumber', 'private');
        // Envoi de la requête
        $query = ldap_search($ldap, $this->connectLdapService->getBasePeople(), $filter, $justThese);
        // Récupération des réponses de la requête
        $result = ldap_get_entries($ldap, $query);
        
        if ($result[0]['private'][0] == "LR") {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Contrainte n°2 : Suppression des numéros de chambres
     * Retourne array string
     */
    public function removeChambers($personnesTotal) {
        // Créer un nouveau tableau
        $personnes = array();
        
        // Parcourir les personnes
        for ($i=0; $i < count($personnesTotal)-1; $i++) {

            // Si ce n'est pas une chambre, l'ajouter au tableau
            if ($personnesTotal[$i]['hierarchysv'][0] != "PATIENTS/CHIC") {
                $personnes[$i] = $personnesTotal[$i];
            }
        }

        return $personnes;
    }

}