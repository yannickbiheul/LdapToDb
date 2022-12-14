<?php

namespace App\Service;

use App\Entity\Entree;
use App\Entity\Personne;
use App\Entity\PeopleRecord;
use App\Service\ConnectLdapService;
use App\Repository\EntreeRepository;
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
    private $doctrine;
    private $personneRepo;
    private $peopleRecordRepo;
    private $numberRecordRepo;

    /**
     * Constructeur
     * Injection de ConnectLdapService
     */
    public function __construct(ConnectLdapService $connectLdapService,
                                ManagerRegistry $doctrine,
                                PersonneRepository $personneRepo,
                                PeopleRecordRepository $peopleRecordRepo,
                                NumberRecordRepository $numberRecordRepo) {
        $this->connectLdapService = $connectLdapService;
        $this->doctrine = $doctrine;
        $this->personneRepo = $personneRepo;
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