<?php

namespace App\Service;

use App\Service\ConnectLdapService;

class AnnuaireManager
{
    private $ldap;

    /**
     * Connexion au Serveur Ldap
     */
    public function connexionLdap()
    {
        // SE CONNECTER AU SERVEUR LDAP
        $host = $this->connectLdapService->getHost();
        $port = $this->connectLdapService->getPort();
        $this->ldap = Ldap::create('ext_ldap', ['connection_string' => 'ldaps://' . $host . ':' . $port]);
        // SE CONNECTER AVEC UN DN ET UN PASSWORD
        $dn = $this->connectLdapService->getDn();
        $password = $this->connectLdapService->getPassword();
        $this->ldap->bind($dn, $password);
    }

    /**
     * 
     */
    public function callAnnuaire(ConnectLdapService $connectLdapService)
    {
        // SE CONNECTER AU SERVEUR LDAP
        $host = $connectLdapService->getHost();
        $port = $connectLdapService->getPort();
        $this->ldap = Ldap::create('ext_ldap', ['connection_string' => 'ldaps://' . $host . ':' . $port]);
        // SE CONNECTER AVEC UN DN ET UN PASSWORD
        $dn = $connectLdapService->getDn();
        $password = $connectLdapService->getPassword();
        $this->ldap->bind($dn, $password);
        $query = $this->ldap->query('ou=people,ou=CHT,o=AASTRA,dc=DOMAIN,dc=COM', '&(objectclass=peopleRecord)(attr2=DSIN))');
        $results = $query->execute()->toArray();
        return $results;

        /*
        // FAIRE DES REQUETES AU SERVEUR
        $query = $ldap->query('dc=symfony,dc=com', '&(objectclass=peopleRecord)(attr2=DSIN))');
        $results = $query->execute();

        foreach ($results as $entry) {
            // Do something with the results
        }

        // POUR RETOURNER DIRECTEMENT UN TABLEAU
        $results = $query->execute()->toArray();

        // UTILISER L'OPTION "FILTER" POUR RECUPERER DES ATTRIBUTS SPECIFIQUES
        $query = $ldap->query('dc=symfony,dc=com', '...', ['filter' => ['cn', 'mail']);
        */
    }
}