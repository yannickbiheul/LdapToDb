<?php

namespace App\Service;

use App\Entity\Metier;
use App\Repository\MetierRepository;
use App\Repository\PeopleRecordRepository;
use App\Service\ConnectLdapService;
use Doctrine\Persistence\ManagerRegistry;

class MetierManager
{
    private $doctrine;
    private $metierRepo;
    private $peopleRecordRepo;

    /**
     * Constructeur
     * Injection de ConnectLdapService
     */
    public function __construct(ManagerRegistry $doctrine,
                                MetierRepository $metierRepo,
                                PeopleRecordRepository $peopleRecordRepo) {
        $this->doctrine = $doctrine;
        $this->metierRepo = $metierRepo;
        $this->peopleRecordRepo = $peopleRecordRepo;
    }

    /**
     * Récupérer la liste des métiers 
     * Retourne tableau de string
     */
    public function getMetiers() {
        $metiers = array();
        $listPeople = $this->peopleRecordRepo->findAll();

        for ($i=0; $i < count($listPeople); $i++) { 
            if (property_exists($listPeople[$i], 'attr7') && $listPeople[$i]->getAttr7() != null) {
                array_push($metiers, $listPeople[$i]->getAttr7());
            }
        }

        return array_unique($metiers);
    }

    /**
     * Enregistrer les métiers dans la bdd
     * 
     */
    public function enregistrerMetiers() {
        $entityManager = $this->doctrine->getManager();
        $listMetiers = $this->getMetiers();

        foreach ($listMetiers as $key => $value) {
            $metier = new Metier();
            $metier->setNom($value);
            if ($this->metierRepo->findOneBy(['nom' => $metier->getNom()]) == null) {
                $entityManager->persist($metier);
            }
        }

        $entityManager->flush();
    }

}