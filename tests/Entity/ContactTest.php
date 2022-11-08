<?php

namespace App\Tests\Entity;

use App\Entity\Contact;
use PHPUnit\Framework\TestCase;

class ContactTest extends TestCase
{
    /**
     * Test sur les getters et setters du nom
     */
    public function testNom()
    {
        $contact = new Contact();
        $nom = "Test";
        $contact->setNom($nom);

        $this->assertEquals("Test", $contact->getNom());
    }

    /**
     * Test sur les getters et setters du telephone
     */
    public function testTelephone()
    {
        $contact = new Contact();
        $telephone = "Test";
        $contact->setTelephone($telephone);

        $this->assertEquals("Test", $contact->getTelephone());
    }

}