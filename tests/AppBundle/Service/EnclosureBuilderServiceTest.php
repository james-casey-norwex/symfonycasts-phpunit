<?php
/**
 * Created by PhpStorm.
 * User: james.casey
 * Date: 2/4/2019
 * Time: 4:20 PM
 */

namespace Tests\AppBundle\Service;

use AppBundle\Entity\Dinosaur;
use AppBundle\Entity\Enclosure;
use AppBundle\Factory\DinosaurFactory;
use AppBundle\Service\EnclosureBuilderService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class EnclosureBuilderServiceTest extends TestCase
{

    public function testItBuildsAndPersistsEnclosure()
    {
        $mockEntityManager = $this->createMock(EntityManagerInterface::class);

        $mockEntityManager->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(Enclosure::class));

        $mockEntityManager->expects($this->atLeastOnce())
            ->method('flush');

        $mockDinosaurFactory = $this->createMock(DinosaurFactory::class);

        $mockDinosaurFactory->expects($this->exactly(2))
            ->method('growFromSpecification')
            ->willReturn(new Dinosaur())
            ->with($this->isType('string'));

        $builder = new EnclosureBuilderService($mockEntityManager, $mockDinosaurFactory);
        $enclosure = $builder->buildEnclosure(1,2);

        $this->assertCount(1, $enclosure->getSecurities());
        $this->assertCount(2, $enclosure->getDinosaurs());

    }
}
