<?php

namespace App\Service;

use App\Entity\Contact;
use App\Entity\Personne;
use App\Entity\NumberRecord;
use App\Entity\PeopleRecord;
use App\Entity\ContactRecord;
use App\Service\ConnectLdapService;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\NumberRecordRepository;
use App\Repository\PeopleRecordRepository;
use App\Repository\ContactRecordRepository;
use App\Repository\PersonneRepository;

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
    private $contactRecordRepository;
    private $personneRepository;

    /**
     * Constructeur
     * 
     */
    public function __construct(ConnectLdapService $connectLdapService, 
                                ManagerRegistry $doctrine,
                                NumberRecordRepository $numberRecordRepository,
                                PeopleRecordRepository $peopleRecordRepository,
                                ContactRecordRepository $contactRecordRepository,
                                PersonneRepository $personneRepository) {
        $this->connectLdapService = $connectLdapService;
        $this->doctrine = $doctrine;
        $this->numberRecordRepository = $numberRecordRepository;
        $this->peopleRecordRepository = $peopleRecordRepository;
        $this->contactRecordRepository = $contactRecordRepository;
        $this->personneRepository = $personneRepository;
    }

    /**
     * Liste toutes les entrées du Ldap de l'objectClass "contactRecord"
     * Retourne tableau de tableaux "listContactRecords"
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
     * Transforme toutes les entrées du Ldap de l'objectClass "contactRecord" en objet "ContactRecord".
     * Enregistre ces objets si ils ne sont pas présents dans la base de données.
     * 
     */
    public function enregistrerContactRecord() {
        // Récupérer la liste de contactRecord et le manager
        $listContactRecord = $this->listContactRecord();
        $entityManager = $this->doctrine->getManager();

        // Parcourir la liste contactRecords
        for ($i=0; $i < count($listContactRecord)-1; $i++) { 
            // création d'un objet
            $contactRecord = new ContactRecord();
            // Attribution du nom
            $contactRecord->setNom($listContactRecord[$i]['sn'][0]);
            // Attribution du numéro de téléphone
            $contactRecord->setTelephone($listContactRecord[$i]['phonenumber'][0]);
            // Attribution du private
            $contactRecord->setPrivate($listContactRecord[$i]['private'][0]);

            // Vérifier que le contactRecord n'existe pas dans la base de données et qu'il n'est pas en liste rouge
            $exist = $this->contactRecordRepository->findOneBy(['telephone' => $contactRecord->getTelephone()]);
            if ($exist == null && $contactRecord->getPrivate() != 'LR') {
                $entityManager->persist($contactRecord);
            }
        }

        $entityManager->flush();
    }

    /**
     * Liste toutes les entrées du Ldap de l'objectClass "numberRecord"
     * Retourne tableau de tableaux "listNumberRecords"
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
     * Enregistre toutes les entrées du Ldap de l'objectClass "numberRecord" 
     * 
     */
    public function enregistrerNumberRecord() {
        // Récupérer la liste numberRecord et le manager
        $listNumberRecord = $this->listNumberRecord();
        $entityManager = $this->doctrine->getManager();

        // Parcourir la liste numberRecord
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
    public function enregistrerPeopleRecord() {
        $listPeopleRecord = $this->listPeopleRecord();
        $entityManager = $this->doctrine->getManager();

        // Parcourir la liste PEOPLE RECORD
        for ($i=0; $i < count($listPeopleRecord)-1; $i++) { 
            // NUMBER RECORD
            if(array_key_exists('mainlinenumber', $listPeopleRecord[$i])) {
                $private = $this->numberRecordRepository->findOneBy(['phoneNumber' => $listPeopleRecord[$i]['mainlinenumber'][0]])->getPrivate();
            } else if(array_key_exists('didnumbers', $listPeopleRecord[$i])) {
                if($this->numberRecordRepository->findOneBy(['phoneNumber' => $listPeopleRecord[$i]['didnumbers'][0]]) != null) {
                    $private = $this->numberRecordRepository->findOneBy(['phoneNumber' => $listPeopleRecord[$i]['didnumbers'][0]])->getPrivate();
                }
            } else {
                $private = 'LV';
            }
            
            // Si le numéro n'est pas un numéro de chambre et qu'il n'est pas en liste rouge
            if ($listPeopleRecord[$i]['hierarchysv'][0] != 'PATIENTS/CHIC' && $private != 'LR') {
                // création d'un objet
                $peopleRecord = new PeopleRecord();
                $personne = new Personne();

                // SN
                $peopleRecord->setSn(strtoupper($listPeopleRecord[$i]['sn'][0]));
                $personne->setNom(strtoupper($listPeopleRecord[$i]['sn'][0]));

                // DISPLAY GN
                if ($listPeopleRecord[$i]['displaygn'][0] != " " && $listPeopleRecord[$i]['displaygn'][0] != null) {
                    $peopleRecord->setDisplayGn($listPeopleRecord[$i]['displaygn'][0]);
                    $personne->setPrenom($listPeopleRecord[$i]['displaygn'][0]);
                } else {
                    $peopleRecord->setDisplayGn(null);
                    $personne->setPrenom(null);
                }

                // MAIN LINE NUMBER
                if (array_key_exists('mainlinenumber', $listPeopleRecord[$i])) {
                    $peopleRecord->setMainLineNumber($listPeopleRecord[$i]['mainlinenumber'][0]);
                    $personne->setTelCourt($listPeopleRecord[$i]['mainlinenumber'][0]);
                } else {
                    $peopleRecord->setMainLineNumber(null);
                    $personne->setTelCourt(null);
                }

                // DID NUMBERS
                if (array_key_exists('didnumbers', $listPeopleRecord[$i])) {
                    $peopleRecord->setDidNumbers($listPeopleRecord[$i]['didnumbers'][0]);
                    $personne->setTelLong($listPeopleRecord[$i]['didnumbers'][0]);
                } else {
                    $peopleRecord->setDidNumbers(null);
                    $personne->setTelLong(null);
                }

                // MAIL
                if (array_key_exists('mail', $listPeopleRecord[$i])) {
                    $peopleRecord->setMail($listPeopleRecord[$i]['mail'][0]);
                    $personne->setMail($listPeopleRecord[$i]['mail'][0]);
                } else {
                    $peopleRecord->setMail(null);
                    $personne->setMail(null);
                }

                // HIERARCHY SV
                $peopleRecord->setHierarchySV($listPeopleRecord[$i]['hierarchysv'][0]);

                // ATTR 1
                if (array_key_exists('attr1', $listPeopleRecord[$i])) {
                    $peopleRecord->setAttr1($listPeopleRecord[$i]['attr1'][0]);
                    $personne->setPole($listPeopleRecord[$i]['attr1'][0]);
                } else {
                    $peopleRecord->setAttr1(null);
                    $personne->setPole(null);
                }

                // ATTR 5
                if (array_key_exists('attr5', $listPeopleRecord[$i])) {
                    $peopleRecord->setAttr5($listPeopleRecord[$i]['attr5'][0]);
                    $personne->setHopital($listPeopleRecord[$i]['attr5'][0]);
                } else {
                    $peopleRecord->setAttr5(null);
                    $personne->setHopital(null);
                }

                // ATTR 6
                if (array_key_exists('attr6', $listPeopleRecord[$i])) {
                    $peopleRecord->setAttr6($listPeopleRecord[$i]['attr6'][0]);
                    $personne->setBatiment($listPeopleRecord[$i]['attr6'][0]);
                } else {
                    $peopleRecord->setAttr6(null);
                    $personne->setBatiment(null);
                }

                // ATTR 7
                if (array_key_exists('attr7', $listPeopleRecord[$i])) {
                    $peopleRecord->setAttr7($listPeopleRecord[$i]['attr7'][0]);
                    $personne->setMetier($listPeopleRecord[$i]['attr7'][0]);
                } else {
                    $peopleRecord->setAttr7(null);
                    $personne->setMetier(null);
                }

                // CLE UID
                $peopleRecord->setCleUid($listPeopleRecord[$i]['cleuid'][0]);
                $personne->setCleUid($listPeopleRecord[$i]['cleuid'][0]);

                // Vérifier qu'il n'existe pas dans la base de données
                $exist = $this->peopleRecordRepository->findOneBy(['cleUid' => $peopleRecord->getCleUid()]);
                // dd($exist);
                $personneExist = $this->personneRepository->findOneBy(['cle_uid' => $personne->getCleUid()]);
                if ($personneExist == null) {
                    $entityManager->persist($personne);
                }
                if ($exist == null) {
                    $entityManager->persist($peopleRecord);
                }
            }
            
        }
        
        $entityManager->flush();
    }

    /**
     * Enregistre tout
     */
    public function enregistrerTout() {
        $this->enregistrerContactRecord();
        $this->enregistrerNumberRecord();
        $this->enregistrerPeopleRecord();
    }

}