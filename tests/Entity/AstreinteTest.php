<?php

namespace App\Tests\Entity;

use App\Entity\Astreinte;
use PHPUnit\Framework\TestCase;

class AstreinteTest extends TestCase
{
    /**
     * Test sur les getters et setters du service
     */
    public function testService()
    {
        $astreinte = new Astreinte();
        $nom = "Service 1";
        $astreinte->setService($nom);

        $this->assertEquals("Service 1", $astreinte->getService());
    }

    /**
     * Test sur les getters et setters du titre
     */
    public function testTitre()
    {
        $astreinte = new Astreinte();
        $nom = "Titre 1";
        $astreinte->setTitre($nom);

        $this->assertEquals("Titre 1", $astreinte->getTitre());
    }

    /**
     * Test sur les getters et setters du nom
     */
    public function testNom()
    {
        $astreinte = new Astreinte();
        $nom = "Nom 1";
        $astreinte->setNom($nom);

        $this->assertEquals("Nom 1", $astreinte->getNom());
    }

    /**
     * Test sur les getters et setters du SurPlace
     */
    public function testSurPlace()
    {
        $astreinte = new Astreinte();
        $nom = "SurPlace 1";
        $astreinte->setSurPlace($nom);

        $this->assertEquals("SurPlace 1", $astreinte->getSurPlace());
    }

    /**
     * Test sur les getters et setters de la description
     */
    public function testDescription()
    {
        $astreinte = new Astreinte();
        $nom = "Description 1";
        $astreinte->setDescription($nom);

        $this->assertEquals("Description 1", $astreinte->getDescription());
    }

}