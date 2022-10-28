<?php

namespace App\Tests\Entity;

use App\Entity\Pole;
use App\Entity\Hopital;
use App\Entity\Batiment;
use PHPUnit\Framework\TestCase;

class PoleTest extends TestCase
{
    /**
     * Test sur les getters et setters du nom
     */
    public function testNom()
    {
        $pole = new Pole();
        $nom = "Test";
        $pole->setnom($nom);

        $this->assertEquals("Test", $pole->getNom());
    }

    /**
     * test sur les getters et setters du bâtiment
     */
    public function testBatiment()
    {
        // Créer un pôle
        $pole = new pole();
        $pole->setNom('pole1');
        // Créer un bâtiment
        $batiment = new Batiment();
        $batiment->setNom('batiment1');
        // Ajouter le pôle au bâtiment
        $pole->setBatiment($batiment);
        // Vérifier que le pôle est bien ajouté
        $this->assertEquals($batiment, $pole->getBatiment());
    }

}