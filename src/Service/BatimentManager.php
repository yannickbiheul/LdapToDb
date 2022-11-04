<?php

namespace App\Service;

use App\Entity\Batiment;
use App\Repository\BatimentRepository;
use App\Repository\HopitalRepository;
use App\Repository\PeopleRecordRepository;
use Doctrine\Persistence\ManagerRegistry;

class BatimentManager
{
    private $doctrine;
    private $batimentRepo;
    private $hopitalRepo;
    private $peopleRecordRepo;

    /**
     * Constructeur
     * Injection de ConnectLdapService
     */
    public function __construct(ManagerRegistry $doctrine, 
                                BatimentRepository $batimentRepo, 
                                HopitalRepository $hopitalRepo,
                                PeopleRecordRepository $peopleRecordRepo) {
        $this->doctrine = $doctrine;
        $this->batimentRepo = $batimentRepo;
        $this->hopitalRepo = $hopitalRepo;
        $this->peopleRecordRepo = $peopleRecordRepo;
    }

    /**
     * Récupérer la liste des bâtiments 
     * Retourne tableau de string
     */
    public function getBatiments() {
        $batiments = array();
        $listPeople = $this->peopleRecordRepo->findAll();
        
        for ($i=0; $i < count($listPeople); $i++) { 
            if (property_exists($listPeople[$i], 'attr6') && $listPeople[$i]->getAttr6() != null) {
                $tableau = array();
                array_push($tableau, $listPeople[$i]->getAttr6());
                array_push($tableau, $listPeople[$i]->getAttr5());
                array_push($batiments, $tableau);
            }
        }
        $test = array();
        foreach ($batiments as $key => $value) {
            
            if (!in_array($value, $test)) {
                array_push($test, $value);
            }
        }
        
        return $test;
    }

    /**
     * Enregistre les bâtiments dans la bdd
     * 
     */
    public function enregistrerBatiments() {
        $entityManager = $this->doctrine->getManager();
        $listBatiments = $this->getBatiments();
       
        foreach ($listBatiments as $key => $value) { 
            $batiment = new Batiment();
            
            if ($this->batimentRepo->findOneBy(['nom' => $listBatiments[$key][0]]) == null) {
                $hopital = $this->hopitalRepo->findOneBy(['nom' => $listBatiments[$key][1]]);
                $batiment->setNom($listBatiments[$key][0]);
                $batiment->setHopital($hopital);
                $entityManager->persist($batiment);
            }

            $entityManager->flush();
        }
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
        
        // Vérifier que le bâtiment est bien relié à un hôpital
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