<?php

namespace Tests\AppBundle\Factory;


use AppBundle\Entity\Dinosaur;
use AppBundle\Factory\DinosaurFactory;
use AppBundle\Service\DinosaurLengthDeterminator;
use PHPUnit\Framework\TestCase;

class DinosaurFactoryTest extends TestCase
{
    /**
     * @var DinosaurFactory
     */
    private $factory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $lengthDeterminator;

    public function setUp()
    {
        $this->lengthDeterminator = $this
            ->createMock(DinosaurLengthDeterminator::class);
        $this->factory = new DinosaurFactory($this->lengthDeterminator);
    }

    public function testItGrowsAVelociraptor()
    {
        $dinosaur = $this->factory->growVelociraptor(5);

        self::assertInstanceOf(Dinosaur::class, $dinosaur);
        self::assertInternalType('string',$dinosaur->getGenus());
        self::assertSame('Velociraptor',$dinosaur->getGenus());
        self::assertSame(5,$dinosaur->getLength());
    }

    public function testIsGrowsATriceratops()
    {
        $this->markTestIncomplete('Waiting');
    }


    public function testItGrowsABabyVelociraptor()
    {
        if(!class_exists('Nanny')) {
            $this->markTestSkipped('nobody');
        }

        $dinosaur = $this->factory->growVelociraptor(1);
        $this->assertSame(1, $dinosaur->getLength(1));
    }

    /**
     * @dataProvider getSpecificationTests
     */
    public function testItGrowsADinosaurFromASpecification(
        string $spec,
        bool $expectedIsCarnivorous)
    {

        $this->lengthDeterminator
            ->expects($this->once())
            ->method('getLengthFromSpecification')
            ->with($spec)
            ->willReturn(20);

        $dinosaur = $this->factory->growFromSpecification($spec);

        $this->assertSame($expectedIsCarnivorous, $dinosaur->isCarnivorous());
        $this->assertSame(20, $dinosaur->getLength());

    }

    public function getSpecificationTests()
    {
        return [
            ['large carnivorous dinosaur', true],
            ['all the cookies', false],
            ['large herbivore', false],

        ];
    }





    //TODO: start at 9. handle object dependencies






}