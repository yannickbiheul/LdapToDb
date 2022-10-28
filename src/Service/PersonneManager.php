<?php

namespace App\Service;

use App\Entity\Personne;
use App\Service\ConnectLdapService;

/**
 * Contrainte n°1 : Ne pas afficher les lignes rouges
 * Contrainte n°2 : Ne pas afficher les numéros de chambres
 */
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
     * Liste les personnes
     * 
     */
    public function listPersonnes() {
        // Connexion au Ldap
        $ldap = $this->connectLdapService->connexionLdap();
        // Création d'un filtre de requête
        $filter = '(&(objectClass=peopleRecord)(sn=*))';
        // Tableau des attributs demandés
        $justThese = array('hierarchySV', 'sn', 'givenName', 'displayGn', 'mainLineNumber', 'didNumbers', 'mail', 'attr1', 'attr3', 'attr7', 'cleUid');
        // Envoi de la requête
        $query = ldap_search($ldap, $this->connectLdapService->getBasePeople(), $filter, $justThese);
        // Récupération des réponses de la requête
        $reponses = ldap_get_entries($ldap, $query);
        // Afficher ces réponses
        // dd($reponses[0]['displaygn'][0]);

        // Création d'un tableau pour stocker les personnes
        $personnes = array();
        // parcourir les réponses
        for ($i=0; $i < count($reponses); $i++) { 

            // Création d'un objet Personne
            $personne = new Personne();

            // Ajouter le prénom
            if((isset($reponses[$i]['displaygn'][0]) && $reponses[$i]['displaygn'][0] != " ") || 
                (isset($reponses[$i]['givenname'][0]) && $reponses[$i]['givenname'][0] != " ")) {
                $personne->setPrenom($reponses[$i]['displaygn'][0]);
            } else {
                $personne->setPrenom('Valeur nulle');
            }
            // Ajouter le nom
            if (isset($reponses[$i]['sn'][0]) && $reponses[$i]['sn'][0] != " ") {
                $personne->setNom($reponses[$i]['sn'][0]);
            } else {
                $personne->setNom("Valeur nulle");
            }
            // Ajouter le téléphone long
            if (isset($reponses[$i]['didnumbers'][0]) && $reponses[$i]['didnumbers'][0] != " ") {
                $personne->setTelephoneLong($reponses[$i]['didnumbers'][0]);
            } else {
                $personne->setTelephoneLong("Valeur nulle");
            }
            // Ajouter le téléphone court
            if (isset($reponses[$i]['mainlinenumber'][0]) && $reponses[$i]['mainlinenumber'][0] != " ") {
                $personne->setTelephoneCourt($reponses[$i]['mainlinenumber'][0]);
            } else {
                $personne->setTelephoneCourt("Valeur nulle");
            }
            // Ajouter le mail
            if (isset($reponses[$i]['mail'][0]) && $reponses[$i]['mail'][0] != " ") {
                $personne->setMail($reponses[$i]['mail'][0]);
            } else {
                $personne->setMail("Valeur nulle");
            }

            $personnes[$i] = $personne;

            // Si l'objet contient un prénom et un nom 
            if ($personne->getPrenom() != "Valeur nulle" && $personne->getNom() != "Valeur nulle") {
                $personnes[$i] = $personne;
            } else {
                unset($personnes[$i]);
            }

        }

        dd($personnes);

    }

    /**
     * Contrainte n°1 : Vérifier que la ligne n'est pas une ligne rouge : 
     * 
     */
    public function checkLigneRouge($numeroCourt) {
        // Connexion au Ldap
        $ldap = $this->connectLdapService->connexionLdap();
        // Création d'un filtre de requête
        $filter = '(&(objectClass=numberRecord)(phoneNumber=' . $numeroCourt .'))';
        // Tableau des attributs demandés
        $justThese = array('mainLineNumber', 'private');
        // Envoi de la requête
        $query = ldap_search($ldap, $this->connectLdapService->getBasePeople(), $filter, $justThese);
        // Récupération des réponses de la requête
        $reponses = ldap_get_entries($ldap, $query);
        // Afficher ces réponses
        // dd($reponses[0]['private'][0]);

        // Si la valeur est "LR", alors c'est une liste rouge
        if($reponses[0]['private'][0] == "LR" && !defined($reponses[0]['private'][0])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Contrainte n°2 : Vérifier que le numéro n'est pas un numéro de chambre : 
     * 
     */
    public function checkNumeroChambre($numeroCourt) {
        // Connexion au Ldap
        $ldap = $this->connectLdapService->connexionLdap();
        // Création d'un filtre de requête
        $filter = '(&(objectClass=peopleRecord)(phoneNumber=' . $numeroCourt .'))';
        // Tableau des attributs demandés
        $justThese = array('hierarchySV');
        // Envoi de la requête
        $query = ldap_search($ldap, $this->connectLdapService->getBasePeople(), $filter, $justThese);
        // Récupération des réponses de la requête
        $reponses = ldap_get_entries($ldap, $query);
        // Afficher ces réponses
        dd($reponses);
        return true;
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