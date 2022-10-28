<?php

namespace App\Service;

use App\Entity\Pole;
use App\Repository\BatimentRepository;
use App\Repository\PoleRepository;
use App\Service\ConnectLdapService;
use Doctrine\Persistence\ManagerRegistry;

class PoleManager
{
    private $ldap;
    private $connectLdapService;
    private $doctrine;
    private $poleRepo;
    private $batimentRepo;

    /**
     * Constructeur
     * Injection de ConnectLdapService
     */
public function __construct(ConnectLdapService $connectLdapService, 
                            ManagerRegistry $doctrine, 
                            PoleRepository $poleRepo, 
                            BatimentRepository $batimentRepo) {
        $this->connectLdapService = $connectLdapService;
        $this->doctrine = $doctrine;
        $this->poleRepo = $poleRepo;
        $this->batimentRepo = $batimentRepo;
    }

    /**
     * Lister tous les pôles : 
     * Retourne array String
     */
    public function listPoles()
    {
        // Connexion au Ldap
        $ldap = $this->connectLdapService->connexionLdap();
        // Création d'un filtre de requête
        $filter = 'objectClass=peopleRecord';
        // Tableau des attributs demandés
        $justThese = array('attr1');
        // Envoi de la requête
        $query = ldap_search($ldap, $this->connectLdapService->getBasePeople(), $filter, $justThese);
        // Récupération des réponses de la requête
        $infos = ldap_get_entries($ldap, $query);
        // Remplissage du tableau de pôles
        $tableau = array();
        for ($i=0; $i < count($infos); $i++) { 
            if (isset($infos[$i]['attr1'])) {
                $tableau[$i] = $infos[$i]['attr1'][0];
            } 
        }

        return array_unique($tableau);
    }

    /**
     * Trouver le bâtiment du pôle : 
     * Retourne Batiment
     */
    public function findBatiment($nomPole) {
        // Connexion au Ldap
        $ldap = $this->connectLdapService->connexionLdap();
        // Création d'un filtre de requête
        $filter = 'attr1='.$nomPole.'';
        // Tableau des attributs demandés
        $justThese = array('attr6');
        // Envoi de la requête
        $query = ldap_search($ldap, $this->connectLdapService->getBasePeople(), $filter, $justThese);
        // Récupération des réponses de la requête
        $infos = ldap_get_entries($ldap, $query);
        
        // Vérifier que le pôle est bien relié à un bâtiment
        if (in_array('attr6', $infos[0])) {
            $batiment = $this->batimentRepo->findBy(["nom" => $infos[0]['attr6'][0]]);
            return $batiment;
        }
        return null;
    }

    /**
     * Persister tous les pôles
     * 
     */
    public function savePoles()
    {
        $entityManager = $this->doctrine->getManager();
        $listePoles = $this->listPoles();

        foreach ($listePoles as $key => $value) {
            // Créer un objet 
            $pole = new Pole();
            // Configurer son nom
            $pole->setNom($value);
            // Configurer son Batiment
            if ($this->findBatiment($pole->getNom()) != null) {
                $batiment = $this->findBatiment($pole->getNom());
                $pole->setBatiment($batiment[0]);
            }

            // Vérifier qu'il n'existe pas dans la base de données
            $existe = $this->poleRepo->findBy(["nom" => $pole->getNom()]);
            if (count($existe) == 0) {
                // Persister l'objet
                $entityManager->persist($pole);
            }
        }

        $entityManager->flush();
    }

}