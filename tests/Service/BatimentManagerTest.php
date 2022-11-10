<?php

namespace App\Tests\Service;

use App\Entity\Batiment;
use PHPUnit\Framework\TestCase;

class BatimentManagerTest extends TestCase
{
    /**
     * Test liste de bâtiments
     */
    public function testGetBatiments(): void
    {
        $batiments = array();

        for ($i=0; $i < 10; $i++) { 
            $batiments[$i] = new Batiment();
            $batiments[$i]->setNom("test $i");
        }

        $this->assertTrue(count($batiments) == 10);
    }
}
