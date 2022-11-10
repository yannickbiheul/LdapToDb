<?php

namespace App\Tests\Service;

use App\Entity\Service;
use PHPUnit\Framework\TestCase;

class ServiceManagerTest extends TestCase
{
    public function testGetServices(): void
    {
        $services = array();

        for ($i=0; $i < 10; $i++) { 
            $services[$i] = new Service();
            $services[$i]->setNom("test $i");
        }

        $this->assertTrue(count($services) == 10);
    }
}
