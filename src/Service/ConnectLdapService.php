<?php

namespace App\Service;

class ConnectLdapService
{
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
}