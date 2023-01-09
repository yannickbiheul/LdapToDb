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
    public function testTelCourt()
    {
        $personne = new Personne();
        $numero = "12345";
        $personne->setTelCourt($numero);

        $this->assertEquals("12345", $personne->getTelCourt());
    }

    /**
     * Test sur les getters et setters du telephone_long
     */
    public function testTelLong()
    {
        $personne = new Personne();
        $numero = "12345";
        $personne->setTelLong($numero);

        $this->assertEquals("12345", $personne->getTelLong());
    }

}