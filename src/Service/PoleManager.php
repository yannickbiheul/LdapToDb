<?php

namespace App\Service;

use App\Entity\Pole;
use App\Entity\Batiment;
use App\Repository\PoleRepository;
use App\Repository\BatimentRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\PeopleRecordRepository;

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
        $tousLesPoles = array();
        $listPeople = $this->peopleRecordRepo->findAll();

        for ($i=0; $i < count($listPeople); $i++) { 
            if (property_exists($listPeople[$i], 'attr1') && $listPeople[$i]->getAttr1() != null) {
                array_push($tousLesPoles, $listPeople[$i]->getAttr1());
            }
        }

        $poles = array_unique($tousLesPoles);
        
        return array_unique($poles);
    }

    /**
     * Enregistrer les pôles dans la bdd
     * 
     */
    public function enregistrerPoles() {
        $entityManager = $this->doctrine->getManager();
        $listPoles = $this->getPoles();
        
        foreach ($listPoles as $key => $value) { 
            
            // Vérifier que le pôle n'existe pas dans la bdd
            if ($this->batimentRepo->findOneBy(['nom' => $listPoles[$key]]) == null) {
                // Création de l'objet
                $pole = new Pole();
                $pole->setNom($listPoles[$key]);

                // Voir s'il existe un bâtiment pour ce pôle
                $nomBatiment = $this->peopleRecordRepo->findOneBy(['attr1' => $listPoles[$key]])->getAttr6();
                
                if ($this->batimentRepo->findOneBy(['nom' => $nomBatiment]) != null) {
                    $batiment = $this->batimentRepo->findOneBy(['nom' => $nomBatiment]);
                    $pole->setBatiment($batiment);
                } else {
                    $pole->setBatiment(null);
                }

                $entityManager->persist($pole);
            }
        }

        $entityManager->flush();
    }
}