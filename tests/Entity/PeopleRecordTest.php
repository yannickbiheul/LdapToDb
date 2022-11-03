<?php

namespace App\Tests\Entity;

use App\Entity\PeopleRecord;
use PHPUnit\Framework\TestCase;

class PeopleRecordTest extends TestCase
{
    /**
     * Test sur les getters et setters du Sn
     */
    public function testSn()
    {
        $peopleRecord = new PeopleRecord();
        $sn = "Test";
        $peopleRecord->setSn($sn);

        $this->assertEquals("Test", $peopleRecord->getSn());
    }

    /**
     * Test sur les getters et setters du DisplayGn
     */
    public function testDisplayGn()
    {
        $peopleRecord = new PeopleRecord();
        $DisplayGn = "Test";
        $peopleRecord->setDisplayGn($DisplayGn);

        $this->assertEquals("Test", $peopleRecord->getDisplayGn());
    }

    /**
     * Test sur les getters et setters du MainLineNumber
     */
    public function testMainLineNumber()
    {
        $peopleRecord = new PeopleRecord();
        $MainLineNumber = "Test";
        $peopleRecord->setMainLineNumber($MainLineNumber);

        $this->assertEquals("Test", $peopleRecord->getMainLineNumber());
    }

    /**
     * Test sur les getters et setters du DidNumbers
     */
    public function testDidNumbers()
    {
        $peopleRecord = new PeopleRecord();
        $DidNumbers = "Test";
        $peopleRecord->setDidNumbers($DidNumbers);

        $this->assertEquals("Test", $peopleRecord->getDidNumbers());
    }

    /**
     * Test sur les getters et setters du Mail
     */
    public function testMail()
    {
        $peopleRecord = new PeopleRecord();
        $Mail = "Test";
        $peopleRecord->setMail($Mail);

        $this->assertEquals("Test", $peopleRecord->getMail());
    }

    /**
     * Test sur les getters et setters du HierarchySV
     */
    public function testHierarchySV()
    {
        $peopleRecord = new PeopleRecord();
        $HierarchySV = "Test";
        $peopleRecord->setHierarchySV($HierarchySV);

        $this->assertEquals("Test", $peopleRecord->getHierarchySV());
    }

    /**
     * Test sur les getters et setters du Attr1
     */
    public function testAttr1()
    {
        $peopleRecord = new PeopleRecord();
        $Attr1 = "Test";
        $peopleRecord->setAttr1($Attr1);

        $this->assertEquals("Test", $peopleRecord->getAttr1());
    }

    /**
     * Test sur les getters et setters du Attr5
     */
    public function testAttr5()
    {
        $peopleRecord = new PeopleRecord();
        $Attr5 = "Test";
        $peopleRecord->setAttr5($Attr5);

        $this->assertEquals("Test", $peopleRecord->getAttr5());
    }

    /**
     * Test sur les getters et setters du Attr6
     */
    public function testAttr6()
    {
        $peopleRecord = new PeopleRecord();
        $Attr6 = "Test";
        $peopleRecord->setAttr6($Attr6);

        $this->assertEquals("Test", $peopleRecord->getAttr6());
    }

    /**
     * Test sur les getters et setters du Attr7
     */
    public function testAttr7()
    {
        $peopleRecord = new PeopleRecord();
        $Attr7 = "Test";
        $peopleRecord->setAttr7($Attr7);

        $this->assertEquals("Test", $peopleRecord->getAttr7());
    }

}