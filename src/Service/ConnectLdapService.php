<?php

namespace App\Service;

class ConnectLdapService
{
    /**
     * Constructeur
     */
    public function __construct(
        public string $dn, 
        public string $password, 
        public string $host, 
        public string $port,
        public string $basePeople,
        public string $baseContact,
    ) {}
    
    public function getDn(): string
    {
        return $this->dn;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): string
    {
        return $this->port;
    }

    public function getBasePeople() {
        return $this->basePeople;
    }

    public function getBaseContact() {
        return $this->baseContact;
    }

    /**
     * Connexion au serveur Ldap en PHP. 
     * Retourne "Ldap/Connexion"
     */
    public function connexionLdap()
    {
        $ldapConnect = ldap_connect('ldap://' . $this->getHost() . ':' . $this->getPort()) or die("L'URI n'est pas la bonne !");
        ldap_set_option($ldapConnect, LDAP_OPT_PROTOCOL_VERSION, 3);
        $ldapBind = ldap_bind($ldapConnect, $this->getDn(), $this->getPassword());
        return $ldapConnect;
    }

}