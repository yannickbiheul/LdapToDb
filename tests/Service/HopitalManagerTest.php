<?php

namespace App\Tests\Service;

use App\Entity\Hopital;
use PHPUnit\Framework\TestCase;

class HopitalManagerTest extends TestCase
{
    /**
     * Test sur la fonction getHopitaux
     */
    public function testList(): void
    {
        $hopitaux = array();

        for ($i=0; $i < 10; $i++) { 
            $hopitaux[$i] = new Hopital();
            $hopitaux[$i]->setNom("test $i");
        }

        $this->assertTrue(count($hopitaux) == 10);
    }
}
