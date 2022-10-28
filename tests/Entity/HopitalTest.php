<?php

namespace App\Tests\Entity;

use App\Entity\Hopital;
use App\Entity\Batiment;
use PHPUnit\Framework\TestCase;

class HopitalTest extends TestCase
{
    /**
     * Test sur les getters et setters du nom
     */
    public function testNom()
    {
        $Hopital = new Hopital();
        $nom = "Test";
        $Hopital->setnom($nom);

        $this->assertEquals("Test", $Hopital->getNom());
    }

    /**
     * Test sur l'ajout d'un bâtiment
     */
    public function testAddBatiment()
    {
        // Créer un hôpital
        $hopital = new Hopital();
        $hopital->setNom('hopital1');
        // Créer un bâtiment
        $batiment = new Batiment();
        $batiment->setNom('batiment1');
        // Ajouter le bâtiment à l'hôpital
        $hopital->addBatiment($batiment);
        // Vérifier que le bâtiment est bien ajouté
        $this->assertEquals($batiment, $hopital->getBatiments()[0]);
    }

    /**
     * Test sur la suppression d'un bâtiment
     */
    public function testRemoveBatiment()
    {
        // Créer un hôpital
        $hopital = new Hopital();
        $hopital->setNom('hopital1');
        // Créer un bâtiment
        $batiment = new Batiment();
        $batiment->setNom('batiment1');
        // Ajouter le bâtiment à l'hôpital
        $hopital->addBatiment($batiment);
        // Supprimer le bâtiment
        $hopital->removeBatiment($batiment);
        // Vérifier que le bâtiment est bien supprimé
        $this->assertEquals(null, $hopital->getBatiments()[0]);
    }

}