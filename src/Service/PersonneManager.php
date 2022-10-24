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

    public function listPersonnes() {
        // Connexion au Ldap
        $ldap = $this->connectLdapService->connexionLdap();
        // Création d'un filtre de requête
        $filter = '(&(objectClass=peopleRecord)(sn=*))';
        // Tableau des attributs demandés
        $justThese = array('hierarchySV', 'sn', 'givenName', 'mainLineNumber', 'didNumbers', 'mail', 'attr1', 'attr3', 'attr7', 'cleUid');
        // Envoi de la requête
        $query = ldap_search($ldap, $this->connectLdapService->getBasePeople(), $filter, $justThese);
        // Récupération des réponses de la requête
        $reponses = ldap_get_entries($ldap, $query);
        // Afficher ces réponses
        // dd($reponses);
        // Crer un tableau qui contiendr des objets Personne
        $personnes = array();
        // Parcourir les réponses une à une
        for ($i=0; $i < count($reponses); $i++) { 
            // Pour chaque réponse :
            // Création d'un objet Personne
            $personne = new Personne();
            // Gestion du prénom
            if (isset($reponses[$i]['givenname'][0]) && $reponses[$i]['givenname'][0] != " ") {
                $personne->setPrenom($reponses[$i]['givenname'][0]);
            } else {
                $personne->setPrenom("Valeur nulle");
            }
            // Gestion du nom
            if (isset($reponses[$i]['sn'][0]) && $reponses[$i]['sn'][0] != " ") {
                $personne->setNom($reponses[$i]['sn'][0]);
            } else {
                $personne->setNom("Valeur nulle");
            }
            // Gestion du téléphone long
            if (isset($reponses[$i]['didnumbers'][0]) && $reponses[$i]['didnumbers'][0] != " ") {
                $personne->setTelephoneLong($reponses[$i]['didnumbers'][0]);
            } else {
                $personne->setTelephoneLong("Valeur nulle");
            }
            // Gestion du téléphone court
            if (isset($reponses[$i]['mainlinenumber'][0]) && $reponses[$i]['mainlinenumber'][0] != " ") {
                $personne->setTelephoneCourt($reponses[$i]['mainlinenumber'][0]);
            } else {
                $personne->setTelephoneCourt("Valeur nulle");
            }
            // Gestion du mail
            if (isset($reponses[$i]['mail'][0]) && $reponses[$i]['mail'][0] != " ") {
                $personne->setMail($reponses[$i]['mail'][0]);
            } else {
                $personne->setMail("Valeur nulle");
            }
            // Si l'objet contient un prénom et un nom, alors c'est une personne
            if ($personne->getPrenom() != "Valeur nulle" && $personne->getNom() != "Valeur nulle" && 
                !$this->checkLigneRouge($personne->getTelephoneCourt())) {

                $personnes[$i] = $personne;

            } else {

                unset($personnes[$i]);
                
            }
            
        }

        
        foreach ($personnes as $personne) {
            echo("Prénom : " . $personne->getPrenom() . 
            " Nom : " . $personne->getNom() . 
            " Tel Court : " . $personne->getTelephoneCourt() . 
            " Tel Long : " . $personne->getTelephoneLong() . 
            " Mail : " . $personne->getMail() . "\n"
        );
        }
        
        // Si la personne existe
        // if(count($info) > 0) {
        //     // Création d'un tableau vide
            
        //     $entitiesTab = array();
        //     for ($i=0; $i < count($info); $i++) { 
        //         // Vérifier si ce n'est pas un numéro de chambre
        //         if (isset($info[$i]['hierarchysv'])) {
                    
        //             if ($info[$i]['hierarchysv'] != 'PATIENTS/CHIC') {

        //                 $tab = array();

        //                 if (isset($info[$i]['givenname'][0]) || $info[$i]['givenname'][0] != "") {
        //                     $tab[0] = $info[$i]['givenname'][0];
        //                 } else {
        //                     $tab[0] = "Valeur nulle";
        //                 }
        //                 if (isset($info[$i]['sn'][0]) || $info[$i]['sn'][0] != "") {
        //                     $tab[0] = $info[$i]['sn'][0];
        //                 } else {
        //                     $tab[0] = "Valeur nulle";
        //                 }
        //                 if (isset($info[$i]['telephonenumber'][0]) || $info[$i]['telephonenumber'][0] != "") {
        //                     $tab[0] = $info[$i]['telephonenumber'][0];
        //                 } else {
        //                     $tab[0] = "Valeur nulle";
        //                 }
        //                 if (isset($info[$i]['didnumbers'][0]) || $info[$i]['didnumbers'][0] != "") {
        //                     $tab[0] = $info[$i]['didnumbers'][0];
        //                 } else {
        //                     $tab[0] = "Valeur nulle";
        //                 }
        //                 if (isset($info[$i]['mail'][0]) || $info[$i]['mail'][0] != "") {
        //                     $tab[0] = $info[$i]['mail'][0];
        //                 } else {
        //                     $tab[0] = "Valeur nulle";
        //                 }
        //                     var_dump($tab);
        //                     dd($info[$i]);
        //                     $entitiesTab[$i] = new Personne(
        //                         (isset($info[$i]['givenname'][0]) || ($info[$i]['givenname'][0] == "")) ? $info[$i]['givenname'][0] : 'valeur nulle',
        //                         isset($info[$i]['sn'][0]) ? $info[$i]['sn'][0] : 'valeur nulle',
        //                         isset($info[$i]['telephonenumber'][0]) ? $info[$i]['telephonenumber'][0] : 'valeur nulle',
        //                         isset($info[$i]['didnumbers'][0]) ? $info[$i]['didnumbers'][0] : 'valeur nulle',
        //                         isset($info[$i]['mail'][0]) ? $info[$i]['mail'][0] : 'valeur nulle',
        //                     );
                            
        //                     // Recherche si le numéro est public ou non
        //                     $filter2 = '(&(objectClass=numberRecord)(phoneNumber=' . $entitiesTab[$i]->getTelephoneCourt() .'))';
        //                     $query2 = ldap_search($ldap, $this->connectLdapService->getBasePeople(), $filter2, $justThese);
        //                     $reponse2 = ldap_get_entries($ldap, $query2);
                            
        //                     if ($reponse2[0]['private'][0] == "LV") {
        //                         $entitiesTab[$i]->setTelephoneCourt($entitiesTab[$i]->getTelephoneCourt());
        //                     } else {
        //                         $entitiesTab[$i]->setTelephoneCourt("Numéro privé");
        //                     }
                        
        //             } else {
        //                 continue;
        //             }
        //         } 
        //     }
        // } else {
        //     return array ("Personne inconnue");
        // }
        // return $entitiesTab;
    }

    /**
     * Contrainte n°1 : Vérifier que la ligne n'est pas une ligne rouge : 
     * Retourne Booleen
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
        if($reponses[0]['private'][0] == "LR") {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Contrainte n°2 : Vérifier que le numéro n'est pas un numéro de chambre : 
     * Retourne Booleen
     */
    public function checkNumeroChambre($hierarchySV) {
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