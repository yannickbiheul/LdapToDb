<?php

namespace App\Service;

use App\Entity\Hopital;
use App\Repository\HopitalRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\PeopleRecordRepository;

class HopitalManager
{
    private $doctrine;
    private $peopleRecordRepository;
    private $hopitalRepo;

    /**
     * Constructeur
     * Injection de ConnectLdapService
     */
    public function __construct(ManagerRegistry $doctrine,
                                PeopleRecordRepository $peopleRecordRepository,
                                HopitalRepository $hopitalRepo) {
        $this->doctrine = $doctrine;
        $this->peopleRecordRepository = $peopleRecordRepository;
        $this->hopitalRepo = $hopitalRepo;
    }

    /**
     * Récupérer la liste des hopitaux 
     * Retourne tableau de string
     */
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
     * Enregistrer les hopitaux dans la bdd
     * 
     */
    public function enregistrerHopitaux() {
        $entityManager = $this->doctrine->getManager();
        $listHopitaux = $this->getHopitaux();

        foreach ($listHopitaux as $key => $value) {
            $hopital = new Hopital();
            $hopital->setNom($value);
            if ($this->hopitalRepo->findOneBy(['nom' => $hopital->getNom()]) == null) {
                $entityManager->persist($hopital);
            }
        }

        $entityManager->flush();
    }

    public function voirHopitaux() {
        return $this->hopitalRepo->findAll();
    }

}