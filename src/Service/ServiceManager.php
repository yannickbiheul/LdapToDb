<?php

namespace App\Service;

use App\Entity\Service;
use App\Repository\BatimentRepository;
use App\Repository\HopitalRepository;
use App\Repository\NumberRecordRepository;
use App\Repository\PeopleRecordRepository;
use App\Repository\PoleRepository;
use App\Service\ConnectLdapService;
use App\Repository\ServiceRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Services
 */
class ServiceManager
{
    private $ldap;
    private $connectLdapService;
    private $doctrine;
    private $serviceRepo;
    private $poleRepo;
    private $batimentRepo;
    private $hopitalRepo;
    private $peopleRecordRepo;
    private $numberRecordRepo;

    /**
     * Constructeur
     * Injection de ConnectLdapService
     */
    public function __construct(ConnectLdapService $connectLdapService, 
                                ManagerRegistry $doctrine, 
                                ServiceRepository $serviceRepo,
                                PoleRepository $poleRepo,
                                BatimentRepository $batimentRepo,
                                HopitalRepository $hopitalRepo,
                                PeopleRecordRepository $peopleRecordRepo,
                                NumberRecordRepository $numberRecordRepo) {
        $this->connectLdapService = $connectLdapService;
        $this->doctrine = $doctrine;
        $this->serviceRepo = $serviceRepo;
        $this->poleRepo = $poleRepo;
        $this->batimentRepo = $batimentRepo;
        $this->hopitalRepo = $hopitalRepo;
        $this->peopleRecordRepo = $peopleRecordRepo;
        $this->numberRecordRepo = $numberRecordRepo;
    }

    /**
     * Récupérer la liste des services 
     * Retourne tableau de string
     */
    public function getServices() {
        $services = array();
        $listPeople = $this->peopleRecordRepo->findAll();

        for ($i=0; $i < count($listPeople); $i++) { 
            $existe = $this->serviceRepo->findOneBy(['nom' => $listPeople[$i]->getSn()]);
            
            if ($listPeople[$i]->getSn() != null && $listPeople[$i]->getDisplayGn() == null) {
                
                $tableau = array();
                $champPrivate = $this->numberRecordRepo->findOneBy(['phoneNumber' => $listPeople[$i]->getMainLineNumber()]);
                if ($champPrivate) {
                    $private = $this->numberRecordRepo->findOneBy(['phoneNumber' => $listPeople[$i]->getMainLineNumber()])->getPrivate();
                } else {
                    $private = null;
                }

                $tableau = ["Nom" => $listPeople[$i]->getSn(),
                              "Clé_UID" => $listPeople[$i]->getCleUid(),
                              "Telephone_Court" => $listPeople[$i]->getMainLineNumber(),
                              "Telephone_Long" => $listPeople[$i]->getDidNumbers(),
                              "Pole" => $listPeople[$i]->getAttr1(),
                              "Hopital" => $listPeople[$i]->getAttr5(),
                              "Batiment" => $listPeople[$i]->getAttr6(),
                              "Hierarchie" => $listPeople[$i]->getHierarchySV(),
                              "Private" => $private];
                
                array_push($services, $tableau);
            }
        }

        return $services;
    }

    /**
     * Enregistrer tous les services
     */
    public function enregistrerServices() {
        $entityManager = $this->doctrine->getManager();
        $listServices = $this->getServices();

        for ($i=0; $i < count($listServices); $i++) { 
            
            // CONTRAINTES: numéros de chambres et liste rouge et service non présent dans la base
            $servicePresent = $this->serviceRepo->findOneBy(['nom' => $listServices[$i]["Nom"]]);

            if ($listServices[$i]["Hierarchie"] != "PATIENTS/CHIC" && $listServices[$i]["Private"] != "LR" && $servicePresent == null) {
                $service = new Service();
                // NOM
                $service->setNom($listServices[$i]["Nom"]);
                // TELEPHONE COURT
                if ($listServices[$i]["Telephone_Court"]) {
                    $service->setTelephoneCourt($listServices[$i]["Telephone_Court"]);
                }
                // TELEPHONE LONG
                $service->setTelephoneLong($listServices[$i]["Telephone_Long"]);
                // POLE
                $pole = $this->poleRepo->findOneBy(['nom' => $listServices[$i]["Pole"]]);
                $service->setPole($pole);
                // BATIMENT
                $batiment = $this->batimentRepo->findOneBy(['nom' => $listServices[$i]["Batiment"]]);
                $service->setBatiment($batiment);
                // HOPITAL
                $hopital = $this->hopitalRepo->findOneBy(['nom' => $listServices[$i]["Hopital"]]);
                $service->setHopital($hopital);

                $entityManager->persist($service);
            }
        }

        $entityManager->flush();
    }

}