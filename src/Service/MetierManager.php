<?php

namespace App\Service;

use App\Entity\Metier;
use App\Repository\MetierRepository;
use App\Service\ConnectLdapService;
use Doctrine\Persistence\ManagerRegistry;

class MetierManager
{
    private $ldap;
    private $connectLdapService;
    private $doctrine;
    private $metierRepo;

    /**
     * Constructeur
     * Injection de ConnectLdapService
     */
    public function __construct(ConnectLdapService $connectLdapService, 
                                ManagerRegistry $doctrine,
                                MetierRepository $metierRepo) {
        $this->connectLdapService = $connectLdapService;
        $this->doctrine = $doctrine;
        $this->metierRepo = $metierRepo;
    }

    /**
     * Lister tous les métiers : 
     * Retourne array String
     */
    public function listMetiers()
    {
        // Connexion au Ldap
        $ldap = $this->connectLdapService->connexionLdap();
        // Création d'un filtre de requête
        $filter = 'objectClass=peopleRecord';
        // Tableau des attributs demandés
        $justThese = array('attr7');
        // Envoi de la requête
        $query = ldap_search($ldap, $this->connectLdapService->getBasePeople(), $filter, $justThese);
        // Récupération des réponses de la requête
        $infos = ldap_get_entries($ldap, $query);
        // Remplissage du tableau de métiers
        $tableau = array();
        for ($i=0; $i < count($infos); $i++) { 
            if (isset($infos[$i]['attr7'])) {
                $tableau[$i] = $infos[$i]['attr7'][0];
            } 
        }

        return array_unique($tableau);
    }

    /**
     * Persister tous les métiers
     * 
     */
    public function saveMetiers()
    {
        $entityManager = $this->doctrine->getManager();
        $listeMetiers = $this->listMetiers();

        foreach ($listeMetiers as $key => $value) {
            // Créer un objet
            $metier = new Metier();
            // Configurer son nom
            $metier->setNom($value);
            
            // Vérifier qu'il n'existe pas dans la base de données
            $existe = $this->metierRepo->findBy(["nom" => $metier->getNom()]);
            if (count($existe) == 0) {
                // Persister l'objet
                $entityManager->persist($metier);
            }
        }

        $entityManager->flush();
    }

}