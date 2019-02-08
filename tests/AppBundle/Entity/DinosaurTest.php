<?php
/**
 * Created by PhpStorm.
 * User: james.casey
 * Date: 12/31/2018
 * Time: 10:48 AM
 */

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Dinosaur;

class DinosaurTest extends \PHPUnit\Framework\TestCase
{
    public function testSettingLength()
    {
        $dinosaur = new Dinosaur();
        $this->assertSame(0, $dinosaur->getLength());
        $dinosaur->setLength(9);
        $this->assertSame(9, $dinosaur->getLength());
    }

    public function testDinosaurHasNotShrunk()
    {
        $dinosaur = new Dinosaur();
        $dinosaur->setLength(15);

        $this->assertGreaterThan(12, $dinosaur->getLength(), 'somemessage.');


    }

    public function testReturnsFullSpecificationOfDinosaur()
    {
        $dinosaur = new Dinosaur();

        $this->assertSame('The Unknown non-carnivorous dinosaur is 0 meters long',
            $dinosaur->getSpecification()
        );
    }

    public function testReturnsFullSpecificationForTyrannosaurus()
    {
        $dinosaur = new Dinosaur('Tyrannosaurus', true);

        $dinosaur->setLength(12);

        $this->assertSame('The Tyrannosaurus carnivorous dinosaur is 12 meters long',
            $dinosaur->getSpecification()
        );

    }





}
