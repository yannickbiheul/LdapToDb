<?php

namespace App\Service;

use App\Model\Pole;
use App\Model\Personne;
use App\Service\ConnectLdapService;

class PersonneManager
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

    /**
     * Trouver les infos d'une personne par son nom
     */
    public function findPeopleByName($sn)
    {
        // Connexion au Ldap
        $ldap = $this->connectLdapService->connexionLdap();
        // Création d'un filtre de requête
        $filter = '(&(objectClass=peopleRecord)(sn=' . $sn . '))';
        // Tableau des attributs demandés
        $justThese = array('hierarchySV', 'sn', 'givenName', 'telephoneNumber', 'private', 'didNumbers', 'mail', 'attr1', 'attr3', 'attr7');
        // Envoi de la requête
        $query = ldap_search($ldap, $this->connectLdapService->getBasePeople(), $filter, $justThese);

        // Récupération des réponses de la requête
        $info = ldap_get_entries($ldap, $query);

        // Si la personne existe
        if(count($info) > 0) {
            // Création d'un tableau vide
            $entitiesTab = array();
            for ($i=0; $i < count($info); $i++) { 
                // Vérifier si ce n'est pas un numéro de chambre
                if (isset($info[$i]['hierarchysv'])) {
                    if ($info[$i]['hierarchysv'] != 'PATIENTS/CHIC') {
                        if (isset($info[$i]['givenname'][0])) {
                            $entitiesTab[$i] = new Personne(
                                $info[$i]['givenname'][0],
                                $info[$i]['sn'][0],
                                $info[$i]['telephonenumber'][0],
                                $info[$i]['didnumbers'][0],
                                $info[$i]['mail'][0],
                                $info[$i]['attr1'][0],
                                $info[$i]['attr7'][0],
                                $info[$i]['attr3'][0],
                            );
                            
                            // Recherche si le numéro est public ou non
                            $filter2 = '(&(objectClass=numberRecord)(phoneNumber=' . $entitiesTab[$i]->getNumeroCourt() .'))';
                            $query2 = ldap_search($ldap, $this->connectLdapService->getBasePeople(), $filter2, $justThese);
                            $reponse2 = ldap_get_entries($ldap, $query2);
                            if ($reponse2[0]['private'][0] == "LV") {
                                $entitiesTab[$i]->setNumeroCourt($entitiesTab[$i]->getNumeroCourt());
                            } else {
                                $entitiesTab[$i]->setNumeroCourt("Numéro privé");
                            }
                        }
                    } else {
                        continue;
                    }
                } 
            }
        } else {
            return array ("Personne inconnue");
        }
        return $entitiesTab;
    }
}