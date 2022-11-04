<?php

namespace App\Service;

use App\Entity\Pole;
use App\Repository\BatimentRepository;
use App\Repository\PeopleRecordRepository;
use App\Repository\PoleRepository;
use Doctrine\Persistence\ManagerRegistry;

class PoleManager
{
    private $doctrine;
    private $poleRepo;
    private $batimentRepo;
    private $peopleRecordRepo;

    /**
     * Constructeur
     * Injection de ConnectLdapService
     */
public function __construct(ManagerRegistry $doctrine, 
                            PoleRepository $poleRepo, 
                            BatimentRepository $batimentRepo,
                            PeopleRecordRepository $peopleRecordRepo) {
        $this->doctrine = $doctrine;
        $this->poleRepo = $poleRepo;
        $this->batimentRepo = $batimentRepo;
        $this->peopleRecordRepo = $peopleRecordRepo;
    }

    /**
     * Récupérer la liste des pôles 
     * Retourne tableau de string
     */
    public function getPoles() {
        $poles = array();
        $listPeople = $this->peopleRecordRepo->findAll();
        
        for ($i=0; $i < count($listPeople); $i++) { 
            if (property_exists($listPeople[$i], 'attr1') && $listPeople[$i]->getAttr1() != null) {
                $tableau = array();
                // Ajouter le pôle
                array_push($tableau, $listPeople[$i]->getAttr1());
                // Ajouter le bâtiment
                array_push($tableau, $listPeople[$i]->getAttr6());
                array_push($poles, $tableau);
            }
        }
        $test = array();
        foreach ($poles as $key => $value) {
            
            if (!in_array($value, $test)) {
                array_push($test, $value);
            }
        }
        
        return $test;
    }

    /**
     * Enregistrer les pôles dans la bdd
     * 
     */
    public function enregistrerPoles() {
        $entityManager = $this->doctrine->getManager();
        $listPoles = $this->getPoles();
       
        foreach ($listPoles as $key => $value) { 
            $pole = new Pole();
            
            // Vérifier que le pôle n'existe pas dans la bdd
            if ($this->poleRepo->findOneBy(['nom' => $listPoles[$key][0]]) == null) {
                $batiment = $this->batimentRepo->findOneBy(['nom' => $listPoles[$key][1]]);
                $pole->setNom($listPoles[$key][0]);
                $pole->setbatiment($batiment);
                $entityManager->persist($pole);
            }

            $entityManager->flush();
        }
    }

}