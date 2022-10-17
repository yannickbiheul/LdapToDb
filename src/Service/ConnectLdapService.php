<?php

namespace App\Service;

use Symfony\Component\Ldap\Ldap;

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

    /**
     * Connexion au Serveur Ldap en Symfony. 
     * Retourne "Ldap\Ldap"
     */
    public function connexionLdapSymfony()
    {
        $ldapConnect = Ldap::create('ext_ldap', ['connection_string' => 'ldaps://' . $this->getHost() . ':' . $this->getPort()]);
        $ldapConnect->bind($this->getDn(), $this->getPassword());
        return $ldapConnect;
    }

    /**
     * Connexion au serveur Ldap en PHP. 
     * Retourne "Ldap/Connexion"
     */
    public function connexionLdapPHP()
    {
        $ldapConnect = ldap_connect('ldap://' . $this->getHost() . ':' . $this->getPort()) or die("L'URI n'est pas la bonne !");
        ldap_set_option($ldapConnect, LDAP_OPT_PROTOCOL_VERSION, 3);
        $ldapBind = ldap_bind($ldapConnect, $this->getDn(), $this->getPassword());
        return $ldapConnect;
    }

}