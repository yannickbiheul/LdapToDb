<?php

namespace App\Tests\Service;

use App\Entity\Pole;
use PHPUnit\Framework\TestCase;

class PoleManagerTest extends TestCase
{
    public function testGetPoles(): void
    {
        $poles = array();

        for ($i=0; $i < 10; $i++) { 
            $poles[$i] = new Pole();
            $poles[$i]->setNom("test $i");
        }

        $this->assertTrue(count($poles) == 10);
    }
}
