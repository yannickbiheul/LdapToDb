<?php

namespace App\Tests\Service;

use App\Entity\Personne;
use PHPUnit\Framework\TestCase;

class PersonneManagerTest extends TestCase
{
    public function testGetPersonnes(): void
    {
        $personnes = array();

        for ($i=0; $i < 10; $i++) { 
            $personnes[$i] = new Personne();
            $personnes[$i]->setNom("test $i");
        }

        $this->assertTrue(count($personnes) == 10);
    }
}
