<?php

namespace App\Service;

use App\Entity\Batiment;
use App\Repository\BatimentRepository;
use App\Repository\HopitalRepository;
use App\Service\ConnectLdapService;
use Doctrine\Persistence\ManagerRegistry;

class BatimentManager
{
    private $ldap;
    private $connectLdapService;
    private $doctrine;
    private $batimentRepo;
    private $hopitalRepo;

    /**
     * Constructeur
     * Injection de ConnectLdapService
     */
    public function __construct(ConnectLdapService $connectLdapService, 
                                ManagerRegistry $doctrine, 
                                BatimentRepository $batimentRepo, 
                                HopitalRepository $hopitalRepo) {
        $this->connectLdapService = $connectLdapService;
        $this->doctrine = $doctrine;
        $this->batimentRepo = $batimentRepo;
        $this->hopitalRepo = $hopitalRepo;
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
     * Trouver l'hôpital du bâtiment : 
     * Retourne Hopital
     */
    public function findHopital($nomBatiment) {
        // Connexion au Ldap
        $ldap = $this->connectLdapService->connexionLdap();
        // Création d'un filtre de requête
        $filter = 'attr6='.$nomBatiment.'';
        // Tableau des attributs demandés
        $justThese = array('attr5');
        // Envoi de la requête
        $query = ldap_search($ldap, $this->connectLdapService->getBasePeople(), $filter, $justThese);
        // Récupération des réponses de la requête
        $infos = ldap_get_entries($ldap, $query);
        
        if (in_array('attr5', $infos[0])) {
            $hopital = $this->hopitalRepo->findBy(["nom" => $infos[0]['attr5'][0]]);
            return $hopital;
        }
        return null;
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
            // Créer un objet
            $batiment = new Batiment();
            // Configurer son nom
            $batiment->setNom($value);
            // Configurer son Hopital
            if ($this->findHopital($batiment->getNom()) != null) {
                $hopital = $this->findHopital($batiment->getNom());
                $batiment->setHopital($hopital[0]);
            }
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