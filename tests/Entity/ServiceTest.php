<?php

namespace App\Tests\Entity;

use App\Entity\Pole;
use App\Entity\Hopital;
use App\Entity\Service;
use App\Entity\Batiment;
use PHPUnit\Framework\TestCase;

class ServiceTest extends TestCase
{
    /**
     * Test sur les getters et setters du nom
     */
    public function testNom()
    {
        $service = new Service();
        $nom = "Test";
        $service->setnom($nom);

        $this->assertEquals("Test", $service->getNom());
    }

    /**
     * Test sur les getters et setters du telephone_court
     */
    public function testTelephoneCourt()
    {
        $service = new Service();
        $numero = "12345";
        $service->setTelephoneCourt($numero);

        $this->assertEquals("12345", $service->getTelephoneCourt());
    }

    /**
     * Test sur les getters et setters du telephone_long
     */
    public function testTelephoneLong()
    {
        $service = new Service();
        $numero = "12345";
        $service->setTelephoneLong($numero);

        $this->assertEquals("12345", $service->getTelephoneLong());
    }

    /**
     * Test sur l'ajout d'un pôle
     */
    public function testAddPole()
    {
        // Créer un service
        $service = new Service();
        $service->setNom('service');
        // Créer un pôle
        $pole = new Pole();
        $pole->setNom('pole 1');
        // Ajouter l pole au service
        $service->setPole($pole);
        // Vérifier que le pôle est bien ajouté
        $this->assertEquals($pole, $service->getPole());
    }

    /**
     * Test sur l'ajout d'un bâtiment
     */
    public function testAddBatiment()
    {
        // Créer un service
        $service = new Service();
        $service->setNom('service');
        // Créer un bâtiment
        $batiment = new Batiment();
        $batiment->setNom('Batiment 1');
        // Ajouter le bâtiment au service
        $service->setBatiment($batiment);
        // Vérifier que le bâtiment est bien ajouté
        $this->assertEquals($batiment, $service->getBatiment());
    }

    /**
     * Test sur l'ajout d'un hôpital
     */
    public function testAddHopital()
    {
        // Créer un service
        $service = new Service();
        $service->setNom('service');
        // Créer un hôpital
        $hopital = new Hopital();
        $hopital->setNom('Hopital 1');
        // Ajouter le hôpital au service
        $service->setHopital($hopital);
        // Vérifier que le hôpital est bien ajouté
        $this->assertEquals($hopital, $service->getHopital());
    }

}