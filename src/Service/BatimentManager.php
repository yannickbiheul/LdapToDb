<?php

namespace App\Service;

use App\Entity\Hopital;
use App\Entity\Batiment;
use App\Repository\HopitalRepository;
use App\Repository\BatimentRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\PeopleRecordRepository;

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
        $tousLesBatiments = array();
        $listPeople = $this->peopleRecordRepo->findAll();

        for ($i=0; $i < count($listPeople); $i++) { 
            if (property_exists($listPeople[$i], 'attr6') && $listPeople[$i]->getAttr6() != null) {
                array_push($tousLesBatiments, $listPeople[$i]->getAttr6());
            }
        }

        $batiments = array_unique($tousLesBatiments);
        
        return array_unique($batiments);
    }

    /**
     * Enregistrer les bâtiments dans la bdd
     * 
     */
    public function enregistrerBatiments() {
        $entityManager = $this->doctrine->getManager();
        $listBatiments = $this->getBatiments();
        
        foreach ($listBatiments as $key => $value) { 
            
            // Vérifier que le bâtiment n'existe pas dans la bdd
            if ($this->batimentRepo->findOneBy(['nom' => $listBatiments[$key]]) == null) {
                // Création de l'objet
                $batiment = new Batiment();
                $batiment->setNom($listBatiments[$key]);

                // Voir s'il existe un hopital pour ce batiment
                $nomHopital = $this->peopleRecordRepo->findOneBy(['attr6' => $listBatiments[$key]])->getAttr5();
                
                if ($this->hopitalRepo->findOneBy(['nom' => $nomHopital]) != null) {
                    $hopital = $this->hopitalRepo->findOneBy(['nom' => $nomHopital]);
                    $batiment->setHopital($hopital);
                } else {
                    $batiment->setHopital(null);
                }

                $entityManager->persist($batiment);
            }
        }

        $entityManager->flush();
    }

    public function voirBatiments() {
        return $this->batimentRepo->findAll();
    }

}