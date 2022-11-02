<?php

namespace App\Service;

use App\Entity\Metier;
use App\Entity\Personne;
use App\Repository\BatimentRepository;
use App\Repository\PoleRepository;
use App\Service\ConnectLdapService;
use App\Repository\MetierRepository;
use App\Repository\HopitalRepository;
use App\Repository\PersonneRepository;
use App\Repository\ServiceRepository;
use Doctrine\Persistence\ManagerRegistry;

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
                                ServiceRepository $serviceRepo) {
        $this->connectLdapService = $connectLdapService;
        $this->metierRepo = $metierRepo;
        $this->doctrine = $doctrine;
        $this->personneRepo = $personneRepo;
        $this->hopitalRepo = $hopitalRepo;
        $this->poleRepo = $poleRepo;
        $this->batimentRepo = $batimentRepo;
        $this->serviceRepo = $serviceRepo;
    }

    /**
     * Lister toutes les personnes : 
     * Retourne array array string
     * 
     */
    public function listPersonnes() {
        // Connexion au Ldap
        $ldap = $this->connectLdapService->connexionLdap();
        // Création d'un filtre de requête
        $filter = '(&(objectClass=peopleRecord)(sn=*))';
        // Tableau des attributs demandés
        $justThese = array('hierarchySV', 'sn', 'givenName', 'displayGn', 'mainLineNumber', 'didNumbers', 'mail', 'attr1', 'attr3', 'attr5', 'attr7', 'cleUid');
        // Envoi de la requête
        $query = ldap_search($ldap, $this->connectLdapService->getBasePeople(), $filter, $justThese);
        // Récupération des réponses de la requête
        $personnesTotal = ldap_get_entries($ldap, $query);

        // Retirer les chambres
        $personnesWithoutChambers = $this->removeChambers($personnesTotal);

        // retirer les listes rouges
        $personnesInGreenList = array();
        foreach ($personnesWithoutChambers as $personne) {
            if(array_key_exists('mainlinenumber', $personne)) {
                if(!$this->isRedList($personne['mainlinenumber'][0])) {
                    array_push($personnesInGreenList, $personne);
                } 
            }
            
        }

        // Séparer les personnes et les services
        $personnes = array();
        $services = array();
        foreach ($personnesInGreenList as $personne) {
            if ($personne['givenname'][0] != " " || $personne['givenname'][0] == null) {
                array_push($personnes, $personne);
            } else {
                array_push($services, $personne);
            }
        }

        return $personnes;
    }

    /**
     * Persister toutes les personnes
     */
    public function savePersonnes()
    {
        $entityManager = $this->doctrine->getManager();
        $listePersonnes = $this->listPersonnes();

        foreach($listePersonnes as $key => $value) {
            // Créer un objet
            $personne = new Personne();
            // Hydrater l'objet
            $personne->setNom($value['sn'][0]);
            $personne->setPrenom($value['displaygn'][0]);
            if (in_array('mail', $value)) {
                $personne->setMail($value['mail'][0]);
            } else {
                $personne->setMail("pas de mail");
            }
            $personne->setTelephoneCourt($value['mainlinenumber'][0]);
            if (in_array('didnumbers', $value)) {
                $personne->setTelephoneLong($value['didnumbers'][0]);
            } else {
                $personne->setTelephoneLong("pas de numéro long");
            }
            
            // Configurer son métier
            if ($this->findMetier($personne->getNom(), $personne->getPrenom()) != null) {
                $metier = $this->findMetier($personne->getNom(), $personne->getPrenom());
                $personne->setMetier($metier);
            } else {
                $personne->setMetier(null);
            }

            // Configurer son hôpital
            if ($this->findHopital($personne->getNom(), $personne->getPrenom()) != null) {
                $hopital = $this->findHopital($personne->getNom(), $personne->getPrenom());
                $personne->setHopital($hopital);
            } else {
                $personne->setHopital(null);
            }

            // Configurer son bâtiment
            if ($this->findBatiment($personne->getNom(), $personne->getPrenom()) != null) {
                $batiment = $this->findBatiment($personne->getNom(), $personne->getPrenom());
                $personne->setBatiment($batiment);
            } else {
                $personne->setBatiment(null);
            }

            // Configurer son pôle
            if ($this->findPole($personne->getNom(), $personne->getPrenom()) != null) {
                $pole = $this->findPole($personne->getNom(), $personne->getPrenom());
                $personne->setPole($pole);
            } else {
                $personne->setPole(null);
            }

            // Configurer son service
            if ($this->findService($personne->getNom(), $personne->getPrenom()) != null) {
                $service = $this->findService($personne->getNom(), $personne->getPrenom());
                $personne->setService($service);
            } else {
                $personne->setService(null);
            }

            // Vérifier qu'il n'existe pas dans la base de données
            $existeNom = $this->personneRepo->findBy(["nom" => $personne->getNom()]);
            if (count($existeNom) == 0) {
                $existePrenom = $this->personneRepo->findBy(["prenom" => $personne->getPrenom()]);
                if (count($existePrenom) == 0) {
                    // Persister l'objet
                    $entityManager->persist($personne);
                } 
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
     * Trouver le service de la personne : 
     * Retourne Service
     */
    public function findService($nomPersonne, $prenomPersonne) {
        // Connexion au Ldap
        $ldap = $this->connectLdapService->connexionLdap();
        // Création d'un filtre de requête
        $filter = '(&(sn='.$nomPersonne.')(displayGn='.$prenomPersonne.'))';
        // Tableau des attributs demandés
        $justThese = array('attr1');
        // Envoi de la requête
        $query = ldap_search($ldap, $this->connectLdapService->getBasePeople(), $filter, $justThese);
        // Récupération des réponses de la requête
        $service = ldap_get_entries($ldap, $query);
        
        // Vérifier que la personne est bien reliée à un service
        if (in_array('attr1', $service[0])) {
            $service = $this->serviceRepo->findBy(["nom" => $service[0]['attr1'][0]]);
            dd($service);
            return $service[0];
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