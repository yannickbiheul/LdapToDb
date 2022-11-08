<?php

namespace App\Service;

use App\Entity\Contact;
use App\Entity\NumberRecord;
use App\Entity\PeopleRecord;
use App\Repository\ContactRepository;
use App\Service\ConnectLdapService;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\NumberRecordRepository;
use App\Repository\PeopleRecordRepository;

/**
 * Récupère toutes les données de l'annuaire, 
 * Transforme ces données en objets, 
 * Applique les contraintes
 */
class RecordManager
{
    private $ldap;
    private $connectLdapService;
    private $doctrine;
    private $numberRecordRepository;
    private $peopleRecordRepository;
    private $contactRepository;

    /**
     * Constructeur
     * 
     */
    public function __construct(ConnectLdapService $connectLdapService, 
                                ManagerRegistry $doctrine,
                                NumberRecordRepository $numberRecordRepository,
                                PeopleRecordRepository $peopleRecordRepository,
                                ContactRepository $contactRepository) {
        $this->connectLdapService = $connectLdapService;
        $this->doctrine = $doctrine;
        $this->numberRecordRepository = $numberRecordRepository;
        $this->peopleRecordRepository = $peopleRecordRepository;
        $this->contactRepository = $contactRepository;
    }

    /**
     * Liste toutes les entrées du Ldap de l'objectClass "contactRecord"
     * Retourne tableau de tableau "listContactRecords"
     */
    public function listContactRecord() {
        // Connexion au Ldap
        $ldap = $this->connectLdapService->connexionLdap();
        // Création d'un filtre de requête
        $filter = '(&(objectClass=contactRecord)(sn=*))';
        // Tableau des attributs demandés
        $justThese = array('phonenumber', 'sn', 'private');
        // Envoi de la requête
        $query = ldap_search($ldap, $this->connectLdapService->getBaseContact(), $filter, $justThese);
        // Récupération des réponses de la requête
        $listContactRecords = ldap_get_entries($ldap, $query);

        return $listContactRecords;
    }

    /**
     * Enregistre toutes les entrées du Ldap de l'objectClass "contactRecord" 
     * 
     */
    public function enregistrerContact() {
        $listContactRecord = $this->listContactRecord();
        $entityManager = $this->doctrine->getManager();

        // Contact RECORD
        for ($i=0; $i < count($listContactRecord)-1; $i++) { 
            if ($listContactRecord[$i]['private'][0] != 'LR') {
                // création d'un objet
                $contact = new Contact();
                // NOM
                $contact->setNom($listContactRecord[$i]['sn'][0]);
                // TELEPHONE
                $contact->setTelephone($listContactRecord[$i]['phonenumber'][0]);
                // Vérifier qu'il n'existe pas dans la base de données
                $exist = $this->contactRepository->findOneBy(['nom' => $contact->getNom()]);
                if ($exist == null) {
                    $entityManager->persist($contact);
                }
            }
        }

        $entityManager->flush();
    }

    /**
     * Liste toutes les entrées du Ldap de l'objectClass "numberRecord"
     * Retourne tableau de tableau "listNumberRecords"
     */
    public function listNumberRecord() {
        // Connexion au Ldap
        $ldap = $this->connectLdapService->connexionLdap();
        // Création d'un filtre de requête
        $filter = '(&(objectClass=numberRecord)(phonenumber=*))';
        // Tableau des attributs demandés
        $justThese = array('phonenumber', 'didnumber', 'private');
        // Envoi de la requête
        $query = ldap_search($ldap, $this->connectLdapService->getBasePeople(), $filter, $justThese);
        // Récupération des réponses de la requête
        $listNumberRecords = ldap_get_entries($ldap, $query);

        return $listNumberRecords;
    }

    /**
     * Enregistre toutes les entrées du Ldap de l'objectClass "peopleRecord" 
     * 
     */
    public function enregistrerNumber() {
        $listNumberRecord = $this->listNumberRecord();
        $entityManager = $this->doctrine->getManager();

        // NUMBER RECORD
        for ($i=0; $i < count($listNumberRecord)-1; $i++) { 

            // création d'un objet
            $numberRecord = new NumberRecord();
            // PHONE NUMBER
            $numberRecord->setPhoneNumber($listNumberRecord[$i]['phonenumber'][0]);
            // DID NUMBER
            if (array_key_exists('didnumber', $listNumberRecord[$i])) {
                $numberRecord->setDidNumber($listNumberRecord[$i]['didnumber'][0]);
            } else {
                $numberRecord->setDidNumber(null);
            }
            // PRIVATE
            $numberRecord->setPrivate($listNumberRecord[$i]['private'][0]);

            // Vérifier qu'il n'existe pas dans la base de données
            $exist = $this->numberRecordRepository->findOneBy(['phoneNumber' => $numberRecord->getPhoneNumber()]);
            if ($exist == null) {
                $entityManager->persist($numberRecord);
            }
            
        }

        $entityManager->flush();
    }

    /**
     * Liste toutes les entrées du Ldap de l'objectClass "peopleRecord"
     * Retourne tableau de tableau "listPeopleRecords"
     */
    public function listPeopleRecord() {
        // Connexion au Ldap
        $ldap = $this->connectLdapService->connexionLdap();
        // Création d'un filtre de requête
        $filter = '(&(objectClass=peopleRecord)(sn=*))';
        // Tableau des attributs demandés
        $justThese = array('sn', 'displaygn', 'givenname', 'mainlinenumber', 'didnumbers', 'mail', 'hierarchysv', 'attr1', 'attr5', 'attr6', 'attr7', 'cleuid');
        // Envoi de la requête
        $query = ldap_search($ldap, $this->connectLdapService->getBasePeople(), $filter, $justThese);
        // Récupération des réponses de la requête
        $listPeopleRecords = ldap_get_entries($ldap, $query);
        
        return $listPeopleRecords;
    }

    /**
     * Enregistre toutes les entrées du Ldap de l'objectClass "peopleRecord"
     * Transforme ces entrées en objets 
    */
    public function enregistrerPeople() {
        $listPeopleRecord = $this->listPeopleRecord();
        $entityManager = $this->doctrine->getManager();

        // PEOPLE RECORD
        for ($i=0; $i < count($listPeopleRecord)-1; $i++) { 
            // création d'un objet
            $peopleRecord = new PeopleRecord();

            // SN
            $peopleRecord->setSn($listPeopleRecord[$i]['sn'][0]);
            // DISPLAY GN
            if ($listPeopleRecord[$i]['displaygn'][0] != " " && $listPeopleRecord[$i]['displaygn'][0] != null) {
                $peopleRecord->setDisplayGn($listPeopleRecord[$i]['displaygn'][0]);
            } else {
                $peopleRecord->setDisplayGn(null);
            }
            // MAIN LINE NUMBER
            if (array_key_exists('mainlinenumber', $listPeopleRecord[$i])) {
                $peopleRecord->setMainLineNumber($listPeopleRecord[$i]['mainlinenumber'][0]);
            } else {
                $peopleRecord->setMainLineNumber(null);
            }
            // DID NUMBERS
            if (array_key_exists('didnumbers', $listPeopleRecord[$i])) {
                $peopleRecord->setDidNumbers($listPeopleRecord[$i]['didnumbers'][0]);
            } else {
                $peopleRecord->setDidNumbers(null);
            }
            // MAIL
            if (array_key_exists('mail', $listPeopleRecord[$i])) {
                $peopleRecord->setMail($listPeopleRecord[$i]['mail'][0]);
            } else {
                $peopleRecord->setMail(null);
            }
            // HIERARCHY SV
            $peopleRecord->setHierarchySV($listPeopleRecord[$i]['hierarchysv'][0]);
            // ATTR 1
            if (array_key_exists('attr1', $listPeopleRecord[$i])) {
                $peopleRecord->setAttr1($listPeopleRecord[$i]['attr1'][0]);
            } else {
                $peopleRecord->setAttr1(null);
            }
            // ATTR 5
            if (array_key_exists('attr5', $listPeopleRecord[$i])) {
                $peopleRecord->setAttr5($listPeopleRecord[$i]['attr5'][0]);
            } else {
                $peopleRecord->setAttr5(null);
            }
            // ATTR 6
            if (array_key_exists('attr6', $listPeopleRecord[$i])) {
                $peopleRecord->setAttr6($listPeopleRecord[$i]['attr6'][0]);
            } else {
                $peopleRecord->setAttr6(null);
            }
            // ATTR 7
            if (array_key_exists('attr7', $listPeopleRecord[$i])) {
                $peopleRecord->setAttr7($listPeopleRecord[$i]['attr7'][0]);
            } else {
                $peopleRecord->setAttr7(null);
            }

            // CLE UID
            $peopleRecord->setCleUid($listPeopleRecord[$i]['cleuid'][0]);

            // Vérifier qu'il n'existe pas dans la base de données
            $exist = $this->peopleRecordRepository->findOneBy(['cleUid' => $peopleRecord->getCleUid()]);
            if ($exist == null) {
                $entityManager->persist($peopleRecord);
            }
        }
        
        $entityManager->flush();
    }

    /**
     * Enregistre tout
     */
    public function enregistrerTout() {
        $this->enregistrerNumber();
        $this->enregistrerPeople();
    }

    public function getNumbersGreenList() {
        return $this->numberRecord->findGreenList();
    }

}