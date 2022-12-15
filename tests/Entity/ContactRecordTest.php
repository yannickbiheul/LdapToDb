<?php

namespace App\Tests\Entity;

use App\Entity\ContactRecord;
use PHPUnit\Framework\TestCase;

class ContactRecordTest extends TestCase
{
    /**
     * Test sur les getters et setters du nom
     */
    public function testNom()
    {
        $contactRecord = new ContactRecord();
        $nom = "Test";
        $contactRecord->setNom($nom);

        $this->assertEquals("Test", $contactRecord->getNom());
    }

    /**
     * Test sur les getters et setters du telephone
     */
    public function testTelephone()
    {
        $contactRecord = new ContactRecord();
        $telephone = "Test";
        $contactRecord->setTelephone($telephone);

        $this->assertEquals("Test", $contactRecord->getTelephone());
    }

    /**
     * Test sur les getters et setters du private
     */
    public function testPrivate()
    {
        $contactRecord = new ContactRecord();
        $private = "Test";
        $contactRecord->setPrivate($private);

        $this->assertEquals("Test", $contactRecord->getPrivate());
    }

}