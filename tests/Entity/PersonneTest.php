<?php

namespace App\Tests\Entity;

use App\Entity\Pole;
use App\Entity\Hopital;
use App\Entity\Personne;
use App\Entity\Batiment;
use PHPUnit\Framework\TestCase;

class PersonneTest extends TestCase
{
    /**
     * Test sur les getters et setters du nom
     */
    public function testNom()
    {
        $personne = new Personne();
        $nom = "Test";
        $personne->setnom($nom);

        $this->assertEquals("Test", $personne->getNom());
    }

    /**
     * Test sur les getters et setters du mail
     */
    public function testMail()
    {
        $personne = new Personne();
        $mail = "Test";
        $personne->setMail($mail);

        $this->assertEquals("Test", $personne->getMail());
    }

    /**
     * Test sur les getters et setters du telephone_court
     */
    public function testTelephoneCourt()
    {
        $personne = new Personne();
        $numero = "12345";
        $personne->setTelephoneCourt($numero);

        $this->assertEquals("12345", $personne->getTelephoneCourt());
    }

    /**
     * Test sur les getters et setters du telephone_long
     */
    public function testTelephoneLong()
    {
        $personne = new Personne();
        $numero = "12345";
        $personne->setTelephoneLong($numero);

        $this->assertEquals("12345", $personne->getTelephoneLong());
    }

    /**
     * Test sur l'ajout d'un pôle
     */
    public function testAddPole()
    {
        // Créer une personne
        $personne = new Personne();
        $personne->setNom('personne');
        // Créer un pôle
        $pole = new Pole();
        $pole->setNom('pole 1');
        // Ajouter l pole au personne
        $personne->setPole($pole);
        // Vérifier que le pôle est bien ajouté
        $this->assertEquals($pole, $personne->getPole());
    }

    /**
     * Test sur l'ajout d'un bâtiment
     */
    public function testAddBatiment()
    {
        // Créer une personne
        $personne = new Personne();
        $personne->setNom('personne');
        // Créer un bâtiment
        $batiment = new Batiment();
        $batiment->setNom('Batiment 1');
        // Ajouter le bâtiment au personne
        $personne->setBatiment($batiment);
        // Vérifier que le bâtiment est bien ajouté
        $this->assertEquals($batiment, $personne->getBatiment());
    }

    /**
     * Test sur l'ajout d'un hôpital
     */
    public function testAddHopital()
    {
        // Créer une personne
        $personne = new Personne();
        $personne->setNom('personne');
        // Créer un hôpital
        $hopital = new Hopital();
        $hopital->setNom('Hopital 1');
        // Ajouter le hôpital au personne
        $personne->setHopital($hopital);
        // Vérifier que le hôpital est bien ajouté
        $this->assertEquals($hopital, $personne->getHopital());
    }

}