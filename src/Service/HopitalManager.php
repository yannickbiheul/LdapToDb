<?php

namespace App\Service;

use App\Entity\Hopital;
use App\Repository\HopitalRepository;
use App\Repository\PeopleRecordRepository;
use App\Service\ConnectLdapService;
use Doctrine\Persistence\ManagerRegistry;

class HopitalManager
{
    private $ldap;
    private $connectLdapService;
    private $doctrine;
    private $hopitalRepo;
    private $recordManager;
    private $peopleRecordRepository;

    /**
     * Constructeur
     * Injection de ConnectLdapService
     */
    public function __construct(ConnectLdapService $connectLdapService, 
                                ManagerRegistry $doctrine, 
                                HopitalRepository $hopitalRepo,
                                PeopleRecordRepository $peopleRecordRepository) {
        $this->connectLdapService = $connectLdapService;
        $this->doctrine = $doctrine;
        $this->hopitalRepo = $hopitalRepo;
        $this->peopleRecordRepository = $peopleRecordRepository;
    }

    public function getHopitaux() {
        $hopitaux = array();
        $listPeople = $this->peopleRecordRepository->findAll();

        for ($i=0; $i < count($listPeople); $i++) { 
            if (property_exists($listPeople[$i], 'attr5') && $listPeople[$i]->getAttr5() != null) {
                array_push($hopitaux, $listPeople[$i]->getAttr5());
            }
        }

        return array_unique($hopitaux);
    }

    /**
     * Persister tous les hôpitaux
     * 
     */
    public function saveHopitaux()
    {
        $entityManager = $this->doctrine->getManager();
        $listeHopitaux = $this->getHopitaux();

        foreach ($listeHopitaux as $key => $value) {
            // Créer un objet
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