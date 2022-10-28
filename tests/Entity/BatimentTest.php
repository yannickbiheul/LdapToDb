<?php

namespace App\Tests\Entity;

use App\Entity\Pole;
use App\Entity\Hopital;
use App\Entity\Batiment;
use PHPUnit\Framework\TestCase;

class BatimentTest extends TestCase
{
    /**
     * Test sur les getters et setters du nom
     */
    public function testNom()
    {
        $batiment = new Batiment();
        $nom = "Test";
        $batiment->setnom($nom);

        $this->assertEquals("Test", $batiment->getNom());
    }

    /**
     * Test sur l'ajout d'un pôle
     */
    public function testAddPole()
    {
        // Créer un bâtiment
        $batiment = new Batiment();
        $batiment->setNom('batiment');
        // Créer un pôle
        $pole = new Pole();
        $pole->setNom('pole1');
        // Ajouter le pôle au bâtiment
        $batiment->addPole($pole);
        // Vérifier que le pôle est bien ajouté
        $this->assertEquals($pole, $batiment->getPoles()[0]);
    }

    /**
     * Test sur la suppression d'un pôle
     */
    public function testRemovePole()
    {
        // Créer un bâtiment
        $batiment = new Batiment();
        $batiment->setNom('batiment1');
        // Créer un pôle
        $pole = new Pole();
        $pole->setNom('pole1');
        // Ajouter le pôle au bâtiment
        $batiment->addPole($pole);
        // Supprimer le pôle
        $batiment->removePole($pole);
        // Vérifier que le pôle est bien supprimé
        $this->assertEquals(null, $batiment->getPoles()[0]);
    }

    /**
     * test sur les getters et setters de l'hôpital
     */
    public function testHopital()
    {
        // Créer un hôpital
        $hopital = new Hopital();
        $hopital->setNom('hopital1');
        // Créer un bâtiment
        $batiment = new Batiment();
        $batiment->setNom('batiment1');
        // Ajouter le bâtiment à l'hôpital
        $batiment->setHopital($hopital);
        // Vérifier que le bâtiment est bien ajouté
        $this->assertEquals($hopital, $batiment->getHopital());
    }

}