<?php

namespace App\Service;

use App\Entity\NumberRecord;
use App\Entity\PeopleRecord;
use App\Service\ConnectLdapService;
use Doctrine\Persistence\ManagerRegistry;

class RecordManager
{
    private $ldap;
    private $connectLdapService;
    private $doctrine;

    /**
     * Constructeur
     * Injection de ConnectLdapService
     */
    public function __construct(ConnectLdapService $connectLdapService, 
                                ManagerRegistry $doctrine) {
        $this->connectLdapService = $connectLdapService;
        $this->doctrine = $doctrine;
    }

    /**
     * Liste toutes les entrées du Ldap de l'objectClass "peopleRecord"
     * Retourne Entree
     */
    public function listPeopleRecord() {
        // Connexion au Ldap
        $ldap = $this->connectLdapService->connexionLdap();
        // Création d'un filtre de requête
        $filter = '(&(objectClass=peopleRecord)(sn=*))';
        // Tableau des attributs demandés
        $justThese = array('sn', 'displaygn', 'givenname', 'mainlinenumber', 'didmumbers', 'mail', 'hierarchysv', 'attr1', 'attr5', 'attr6', 'attr7');
        // Envoi de la requête
        $query = ldap_search($ldap, $this->connectLdapService->getBasePeople(), $filter, $justThese);
        // Récupération des réponses de la requête
        $listPeopleRecord = ldap_get_entries($ldap, $query);

        return $listPeopleRecord;
    }

    /**
     * Enregistre toutes les entrées du Ldap de l'objectClass "peopleRecord"
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

            $entityManager->persist($peopleRecord);
        }

        $entityManager->flush();
    }

    /**
     * Liste toutes les entrées du Ldap de l'objectClass "numberRecord"
     * Retourne Entree
     */
    public function listNumberRecord() {
        // Connexion au Ldap
        $ldap = $this->connectLdapService->connexionLdap();
        // Création d'un filtre de requête
        $filter = '(&(objectClass=numberRecord)(phonenumber=*))';
        // Tableau des attributs demandés
        $justThese = array('phonenumber', 'didnumbers', 'private');
        // Envoi de la requête
        $query = ldap_search($ldap, $this->connectLdapService->getBasePeople(), $filter, $justThese);
        // Récupération des réponses de la requête
        $tout = ldap_get_entries($ldap, $query);

        return $tout;
    }

    /**
     * Enregistre toutes les entrées du Ldap de l'objectClass "peopleRecord"
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
            if (array_key_exists('didnumbers', $listNumberRecord[$i])) {
                $numberRecord->setDidNumber($listNumberRecord[$i]['didnumbers'][0]);
            } else {
                $numberRecord->setDidNumber(null);
            }
            // PRIVATE
            $numberRecord->setPrivate($listNumberRecord[$i]['private'][0]);

            $entityManager->persist($numberRecord);
        }

        $entityManager->flush();
    }

    /**
     * Enregistre tout
     */
    public function enregistrerTout() {
        $this->enregistrerPeople();
        $this->enregistrerNumber();
    }

}