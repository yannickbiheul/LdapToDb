<?php

namespace App\Service;

use App\Entity\Batiment;
use App\Repository\BatimentRepository;
use App\Service\ConnectLdapService;
use Doctrine\Persistence\ManagerRegistry;

class BatimentManager
{
    private $ldap;
    private $connectLdapService;
    private $doctrine;
    private $batimentRepo;

    /**
     * Constructeur
     * Injection de ConnectLdapService
     */
    public function __construct(ConnectLdapService $connectLdapService, ManagerRegistry $doctrine, BatimentRepository $batimentRepo) {
        $this->connectLdapService = $connectLdapService;
        $this->doctrine = $doctrine;
        $this->batimentRepo = $batimentRepo;
    }

    /**
     * Lister tous les bâtiments : 
     * Retourne array String
     */
    public function listBatiments()
    {
        // Connexion au Ldap
        $ldap = $this->connectLdapService->connexionLdap();
        // Création d'un filtre de requête
        $filter = 'objectClass=peopleRecord';
        // Tableau des attributs demandés
        $justThese = array('attr6');
        // Envoi de la requête
        $query = ldap_search($ldap, $this->connectLdapService->getBasePeople(), $filter, $justThese);
        // Récupération des réponses de la requête
        $infos = ldap_get_entries($ldap, $query);
        // Remplissage du tableau de bâtiments
        $tableau = array();
        for ($i=0; $i < count($infos); $i++) { 
            if (isset($infos[$i]['attr6'])) {
                $tableau[$i] = $infos[$i]['attr6'][0];
            } 
        }

        return array_unique($tableau);
    }

    /**
     * Persister tous les bâtiments
     * 
     */
    public function saveBatiments()
    {
        $entityManager = $this->doctrine->getManager();
        $listeBatiments = $this->listBatiments();

        foreach ($listeBatiments as $key => $value) {
            // Créer un objet Hopital
            $batiment = new Batiment();
            // Configurer son nom
            $batiment->setNom($value);
            // Vérifier qu'il n'existe pas dans la base de données
            $existe = $this->batimentRepo->findBy(["nom" => $batiment->getNom()]);
            if (count($existe) == 0) {
                // Persister l'objet
                $entityManager->persist($batiment);
            }
        }

        $entityManager->flush();
    }

}