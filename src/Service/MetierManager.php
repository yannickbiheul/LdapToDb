<?php

namespace App\Service;

use App\Entity\Metier;
use App\Service\ConnectLdapService;

class MetierManager
{
    private $ldap;
    private $connectLdapService;

    /**
     * Constructeur
     * Injection de ConnectLdapService
     */
    public function __construct(ConnectLdapService $connectLdapService) {
        $this->connectLdapService = $connectLdapService;
    }

    /**
     * Lister tous les métiers : 
     * Retourne array String
     */
    public function listMetiers()
    {
        // Connexion au Ldap
        $ldap = $this->connectLdapService->connexionLdap();
        // Création d'un filtre de requête
        $filter = 'objectClass=peopleRecord';
        // Tableau des attributs demandés
        $justThese = array('attr7');
        // Envoi de la requête
        $query = ldap_search($ldap, $this->connectLdapService->getBasePeople(), $filter, $justThese);
        // Récupération des réponses de la requête
        $infos = ldap_get_entries($ldap, $query);
        // Remplissage du tableau de bâtiments
        $tableau = array();
        for ($i=0; $i < count($infos); $i++) { 
            if (isset($infos[$i]['attr7'])) {
                $tableau[$i] = $infos[$i]['attr7'][0];
            } 
        }

        return array_unique($tableau);
    }

    /**
     * Persister tous les métiers
     * 
     */
    public function saveMetiers()
    {
        $infos = $this->listMetiers();

        $metiers = array();
        for ($i=0; $i < count($infos); $i++) { 
            $metiers[$i] = new Metier($infos[$i]['attr6'][0]); 
        }
    }

}