<?php

namespace App\Service;

use App\Entity\Batiment;
use App\Service\ConnectLdapService;

class BatimentManager
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
     * Lister tous les bâtiments : 
     * Retourne array String
     */
    public function listBatiments()
    {
        // Connexion au Ldap
        $ldap = $this->connectLdapService->connexionLdap();
        // Création d'un filtre de requête
        $filter = 'objectClass=peopleRecord';
        // Tableau des attributs demandés
        $justThese = array('attr6');
        // Envoi de la requête
        $query = ldap_search($ldap, $this->connectLdapService->getBasePeople(), $filter, $justThese);
        // Récupération des réponses de la requête
        $infos = ldap_get_entries($ldap, $query);
        // Remplissage du tableau de bâtiments
        $tableau = array();
        for ($i=0; $i < count($infos); $i++) { 
            if (isset($infos[$i]['attr6'])) {
                $tableau[$i] = $infos[$i]['attr6'][0];
            } 
        }

        return array_unique($tableau);
    }

    /**
     * Persister tous les bâtiments
     * 
     */
    public function saveBatiments()
    {
        $infos = $this->listBatiments();

        $batiments = array();
        for ($i=0; $i < count($infos); $i++) { 
            $batiments[$i] = new Batiment($infos[$i]['attr6'][0]); 
        }
    }

}