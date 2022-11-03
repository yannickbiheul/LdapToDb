<?php

namespace App\Tests\Entity;

use App\Entity\NumberRecord;
use PHPUnit\Framework\TestCase;

class NumberRecordTest extends TestCase
{
    /**
     * Test sur les getters et setters du phoneNumber
     */
    public function testPhoneNumber()
    {
        $numberRecord = new NumberRecord();
        $number = "Test";
        $numberRecord->setPhoneNumber($number);

        $this->assertEquals("Test", $numberRecord->getPhoneNumber());
    }

    /**
     * Test sur les getters et setters du didNumber
     */
    public function testDidNumber()
    {
        $numberRecord = new NumberRecord();
        $number = "Test";
        $numberRecord->setDidNumber($number);

        $this->assertEquals("Test", $numberRecord->getDidNumber());
    }

    /**
     * Test sur les getters et setters du Private
     */
    public function testPrivate()
    {
        $numberRecord = new NumberRecord();
        $number = "Test";
        $numberRecord->setPrivate($number);

        $this->assertEquals("Test", $numberRecord->getPrivate());
    }

}