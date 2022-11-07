<?php

namespace App\Tests\Entity;

use App\Entity\Metier;
use App\Entity\Personne;
use PHPUnit\Framework\TestCase;

class MetierTest extends TestCase
{
    /**
     * Test sur les getters et setters du nom
     */
    public function testNom()
    {
        $metier = new Metier();
        $nom = "Test";
        $metier->setnom($nom);

        $this->assertEquals("Test", $metier->getNom());
    }

    /**
     * Test sur l'ajout d'une personne
     */
    public function testAddPersonne()
    {
        // Créer un métier
        $metier = new Metier();
        $metier->setNom('metier');
        // Créer une personne
        $personne = new Personne();
        $personne->setNom('Personne 1');
        // Ajouter la personne au métier
        $metier->addPersonne($personne);
        // Vérifier que la personne est bien ajoutée
        $this->assertEquals($personne, $metier->getPersonnes()[0]);
    }

    /**
     * Test sur la suppression d'une personne
     */
    public function testRemovePersonne()
    {
        // Créer un métier
        $metier = new Metier();
        $metier->setNom('Métier 1');
        // Créer une personne
        $personne = new Personne();
        $personne->setNom('Personne 1');
        // Ajouter la personne au métier
        $metier->addPersonne($personne);
        // Supprimer la personne
        $metier->removePersonne($personne);
        // Vérifier que la personne est bien supprimée
        $this->assertEquals(null, $metier->getPersonnes()[0]);
    }

}