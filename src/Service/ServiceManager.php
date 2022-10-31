<?php

namespace App\Service;

use App\Entity\Service;
use App\Service\ConnectLdapService;
use App\Repository\ServiceRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Services
 */
class ServiceManager
{
    private $ldap;
    private $connectLdapService;
    private $doctrine;
    private $serviceRepo;

    /**
     * Constructeur
     * Injection de ConnectLdapService
     */
    public function __construct(ConnectLdapService $connectLdapService, 
                                ManagerRegistry $doctrine, 
                                ServiceRepository $serviceRepo) {
        $this->connectLdapService = $connectLdapService;
        $this->doctrine = $doctrine;
        $this->serviceRepo = $serviceRepo;
    }

    /**
     * Lister tous les services : 
     * Retourne array array string
     * 
     */
    public function listServices() {
        // Connexion au Ldap
        $ldap = $this->connectLdapService->connexionLdap();
        // Création d'un filtre de requête
        $filter = '(&(objectClass=peopleRecord)(sn=*))';
        // Tableau des attributs demandés
        $justThese = array('hierarchySV', 'sn', 'givenName', 'displayGn', 'mainLineNumber', 'didNumbers', 'mail', 'attr1', 'attr3', 'attr7', 'cleUid');
        // Envoi de la requête
        $query = ldap_search($ldap, $this->connectLdapService->getBasePeople(), $filter, $justThese);
        // Récupération des réponses de la requête
        $services = ldap_get_entries($ldap, $query);

        // Séparer les personnes et les services et vérifier que le service n'est pas en liste rouge
        $justServices = array();
        for ($i=0; $i < count($services)-1; $i++) { 
            if (array_key_exists('mainlinenumber', $services[$i])) {
                if ($services[$i]['givenname'][0] == " " || $services[$i]['givenname'][0] == null) {
                    if (!$this->isRedList($services[$i]['mainlinenumber'][0])) {
                        array_push($justServices, $services[$i]);
                    }
                }
            }
        }

        // Retirer les numéros de chambres
        $servicesWithoutChambers = $this->removeChambers($justServices);

        return $servicesWithoutChambers;
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

    /**
     * Persister tous les services
     * 
     */
    public function saveServices()
    {
        $entityManager = $this->doctrine->getManager();
        $listeServices = $this->listServices();

        foreach ($listeServices as $key => $value) {
            // Créer un objet
            $service = new Service();
            // Configurer son nom
            $service->setNom($value['sn'][0]);
            $service->setTelephoneCourt($value['mainlinenumber'][0]);
            
            // Vérifier qu'il n'existe pas dans la base de données
            $existe = $this->serviceRepo->findBy(["nom" => $service->getNom()]);
            if (count($existe) == 0) {
                // Persister l'objet
                $entityManager->persist($service);
            }
        }

        $entityManager->flush();
    }

}