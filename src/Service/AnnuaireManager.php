<?php

namespace App\Service;

use App\Service\ConnectLdapService;

class AnnuaireManager
{
    private $ldap;
    private $connectLdapService;
    private $baseLdap = "ou=people,ou=GHT,o=AASTRA,dc=DOMAIN,dc=COM";

    /**
     * Constructeur
     * Injection de ConnectLdapService
     */
    public function __construct(ConnectLdapService $connectLdapService) {
        $this->connectLdapService = $connectLdapService;
    }

    /**
     * Tester une requête sur l'annuaire méthode Symfony
     */
    public function testAnnuaireSymfony()
    {
        // Connexion au Ldap
        $ldap = $this->connectLdapService->connexionLdapSymfony();
        // Création d'une requête
        $requete = "&(objectclass=peopleRecord)(attr2=DSIN))";
        // Envoi de la requête
        $query = $ldap->query($this->baseLdap, $requete);
        // Retourner la requête sous forme de tableau
        $results = $query->execute()->toArray();
        return $results;
    }

    /**
     * Trouver les infos d'une personne par son nom
     */
    public function findByName($sn)
    {
        // Connexion au Ldap
        $ldap = $this->connectLdapService->connexionLdapPHP();
        // Création d'un filtre de requête
        $filter = '(&(objectClass=peopleRecord)(sn=' . $sn . '))';
        // Tableau des attributs demandés
        $justThese = array('hierarchySV', 'sn', 'givenName', 'telephoneNumber', 'private', 'didNumbers', 'mail', 'attr1', 'attr3', 'attr7');
        // Envoi de la requête
        $query = ldap_search($ldap, $this->baseLdap, $filter, $justThese);

        // Récupération des entrées
        $info = ldap_get_entries($ldap, $query);

        // Si la personne existe
        if(count($info) > 1) {
            for ($i=0; $i < count($info); $i++) { 
                
            }
            // Vérifier si ce n'est pas un numéro de chambre
            if ($info[0]['hierarchysv'] != 'PATIENTS/CHIC') {
                // Récupération du prénom
                $prenom = $info[0]['givenname'][0];
                // Récupération du nom
                $nom = $info[0]['sn'][0];
                // Récupération du numéro court
                $numero = $info[0]['telephonenumber'][0];
                // récupération du numéro long
                $numeroLong = $info[0]['didnumbers'][0];
                // Récupération du mail
                $mail = $info[0]['mail'][0];
                // Récupération du pôle
                $pole = $info[0]['attr1'][0];
                // récupération du métier
                $metier = $info[0]['attr7'][0];
                // Récupération du poste
                $poste =$info[0]['attr3'][0];

                // Recherche si le numéro est public ou non
                $filter2 = '(&(objectClass=numberRecord)(phoneNumber=' . $numero .'))';
                $query2 = ldap_search($ldap, $this->baseLdap, $filter2, $justThese);
                $reponse2 = ldap_get_entries($ldap, $query2) or die ("Error in get entries: ".ldap_error($ldap));
                if ($reponse2[0]['private'][0] == "LV") {
                    $numero = $numero;
                } else {
                    $numero = "Numéro privé";
                }

                $reponses = array($prenom, $nom, $numero, $numeroLong, $mail, $pole, $metier, $poste);
                return $reponses;
            } else {
                return array("Informations interdites");
            }
        } else {
            return array ("Personne inconnue");
        }

    }
}