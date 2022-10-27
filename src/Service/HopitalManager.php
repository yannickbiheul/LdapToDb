<?php

namespace App\Service;

use App\Entity\Hopital;
use App\Repository\HopitalRepository;
use App\Service\ConnectLdapService;
use Doctrine\Persistence\ManagerRegistry;

class HopitalManager
{
    private $ldap;
    private $connectLdapService;
    private $doctrine;
    private $hopitalRepo;

    /**
     * Constructeur
     * Injection de ConnectLdapService
     */
    public function __construct(ConnectLdapService $connectLdapService, ManagerRegistry $doctrine, HopitalRepository $hopitalRepo) {
        $this->connectLdapService = $connectLdapService;
        $this->doctrine = $doctrine;
        $this->hopitalRepo = $hopitalRepo;
    }

    /**
     * Lister tous les hôpitaux : 
     * Retourne array String
     */
    public function listHopitaux()
    {
        // Connexion au Ldap
        $ldap = $this->connectLdapService->connexionLdap();
        // Création d'un filtre de requête
        $filter = 'objectClass=peopleRecord';
        // Tableau des attributs demandés
        $justThese = array('attr5');
        // Envoi de la requête
        $query = ldap_search($ldap, $this->connectLdapService->getBasePeople(), $filter, $justThese);
        // Récupération des réponses de la requête
        $infos = ldap_get_entries($ldap, $query);
        // Remplissage du tableau de bâtiments
        $tableau = array();
        for ($i=0; $i < count($infos); $i++) { 
            if (isset($infos[$i]['attr5'])) {
                $tableau[$i] = $infos[$i]['attr5'][0];
            } 
        }

        return array_unique($tableau);
    }

    /**
     * Persister tous les hôpitaux
     * 
     */
    public function saveHopitaux()
    {
        $entityManager = $this->doctrine->getManager();
        $listeHopitaux = $this->listHopitaux();

        foreach ($listeHopitaux as $key => $value) {
            // Créer un objet Hopital
            $hopital = new Hopital();
            // Configurer son nom
            $hopital->setNom($value);
            // Vérifier qu'il n'existe pas dans la base de données
            $existe = $this->hopitalRepo->findBy(["nom" => $hopital->getNom()]);
            if (count($existe) == 0) {
                // Persister l'objet
                $entityManager->persist($hopital);
            }
        }

        $entityManager->flush();
    }

}