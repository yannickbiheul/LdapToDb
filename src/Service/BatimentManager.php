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
     * Enregistrer les bâtiments dans la bdd
     * 
     */
    public function enregistrerBatiments() {
        $entityManager = $this->doctrine->getManager();
        $listBatiments = $this->getBatiments();
       
        foreach ($listBatiments as $key => $value) { 
            $batiment = new Batiment();
            
            // Vérifier que le bâtiment n'existe pas dans la bdd
            if ($this->batimentRepo->findOneBy(['nom' => $listBatiments[$key][0]]) == null) {
                $hopital = $this->hopitalRepo->findOneBy(['nom' => $listBatiments[$key][1]]);
                $batiment->setNom($listBatiments[$key][0]);
                $batiment->setHopital($hopital);
                $entityManager->persist($batiment);
            }

            $entityManager->flush();
        }
    }

}