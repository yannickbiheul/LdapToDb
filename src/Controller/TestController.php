<?php

namespace App\Controller;

use App\Service\AnnuaireManager;
use Symfony\Component\Ldap\Ldap;
use App\Service\ConnectLdapService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{

    #[Route('/test', name: 'app_test')]
    public function index(AnnuaireManager $annuaire, ConnectLdapService $connectLdapService): Response
    {
        // Initialiser les variables
        $host = $connectLdapService->getHost();
        $port = $connectLdapService->getPort();
        $dn = $connectLdapService->getDn();
        $password = $connectLdapService->getPassword();
        $person = "TAGLIAFERRO";
        $uri = "ldap://".$host.":".$port;
        $base = "ou=people,ou=GHT,o=AASTRA,dc=DOMAIN,dc=COM";
        $filter = "sn=TAGLIAFERRO";
        $justThese = array("sn");

        // Se connecter au ldap
        // $ldap = Ldap::create('ext_ldap', [
        //     'host' => $host,
        //     'encryption' => 'none',
        // ]);
        // $ldap = Ldap::create('ext_ldap', ['connection_string' => 'ldaps://' . $host . ':' . $port]);
        // $ldap->bind($dn, $password);

        // Créer la requête
        
        // $query = $ldap->query($base, $filters);
        // $results = $query->execute();

        // dd(ldap_get_entries($ldap, $results));

        $ldapConnect = ldap_connect($uri) or die("That LDAP-URI was not parseable");
        ldap_set_option($ldapConnect, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapConnect, LDAP_OPT_REFERRALS, 0);
        $ldapBind = ldap_bind($ldapConnect,$dn,$password);

        $sr = ldap_search($ldapConnect, $dn, $filter, $justThese);
        $info = ldap_get_entries($ldapConnect, $sr);

        dd($info);

        return $this->json([
            'message' => 'Test des requêtes Ldap',
            'path' => 'src/Controller/TestController.php',
        ]);
    }
}