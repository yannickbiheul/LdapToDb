<?php

namespace App\Service;

use App\Entity\Hopital;
use App\Service\ConnectLdapService;

class HopitalManager
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
     * Lister tous les hôpitaux : 
     * Retourne array String
     */
    public function listHopitaux()
    {
        // Connexion au Ldap
        $ldap = $this->connectLdapService->connexionLdap();
        // Création d'un filtre de requête
        $filter = 'objectClass=peopleRecord';
        // Tableau des attributs demandés
        $justThese = array('attr5');
        // Envoi de la requête
        $query = ldap_search($ldap, $this->connectLdapService->getBasePeople(), $filter, $justThese);
        // Récupération des réponses de la requête
        $infos = ldap_get_entries($ldap, $query);
        // Remplissage du tableau de bâtiments
        $tableau = array();
        for ($i=0; $i < count($infos); $i++) { 
            if (isset($infos[$i]['attr5'])) {
                $tableau[$i] = $infos[$i]['attr5'][0];
            } 
        }

        return array_unique($tableau);
    }

    /**
     * Persister tous les hôpitaux
     * 
     */
    public function saveHopitals()
    {
        $infos = $this->listHopitals();

        $hopitaux = array();
        for ($i=0; $i < count($infos); $i++) { 
            $hopitaux[$i] = new Hopital($infos[$i]['attr5'][0]); 
        }
    }

}