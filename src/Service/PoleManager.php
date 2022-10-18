<?php

namespace App\Service;

use App\Model\Pole;
use App\Model\Personne;
use App\Service\ConnectLdapService;

class PoleManager
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
     * Lister tous les pôles
     * Retourne array Pole
     */
    public function getPoles()
    {
        // Connexion au Ldap
        $ldap = $this->connectLdapService->connexionLdap();
        // Création d'un filtre de requête
        $filter = 'objectClass=peopleRecord';
        // Tableau des attributs demandés
        $justThese = array('sn','attr1');
        // Envoi de la requête
        $query = ldap_search($ldap, $this->connectLdapService->getBasePeople(), $filter, $justThese);
        // Récupération des réponses de la requête
        $infos = ldap_get_entries($ldap, $query);

        $poles = array();
        for ($i=0; $i < count($infos); $i++) { 
            if (isset($infos[$i]['attr1'])) {
                $poles[$i] = new Pole($infos[$i]['attr1'][0]);
            } 
        }

        return array_unique($poles);
    }

}