<?php

namespace App\Tests\Service;

use App\Entity\Metier;
use PHPUnit\Framework\TestCase;

class MetierManagerTest extends TestCase
{
    public function testGetMetiers(): void
    {
        $metiers = array();

        for ($i=0; $i < 10; $i++) { 
            $metiers[$i] = new Metier();
            $metiers[$i]->setNom("test $i");
        }

        $this->assertTrue(count($metiers) == 10);
    }
}
