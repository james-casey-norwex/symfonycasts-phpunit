<?php
/**
 * Created by PhpStorm.
 * User: james.casey
 * Date: 2/4/2019
 * Time: 5:07 PM
 */

namespace Tests\AppBundle\Service;

use AppBundle\Entity\Dinosaur;
use AppBundle\Entity\Security;
use AppBundle\Factory\DinosaurFactory;
use AppBundle\Service\EnclosureBuilderService;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EnclosureBuilderServiceIntegrationTest extends KernelTestCase
{
    protected function setUp()
    {
        self::bootKernel();

        $this->truncateEntities();
    }


    public function testItBuildsEnclosureWithDefaultSpecification()
    {

        /** @var EnclosureBuilderService $enclosureBuilderService */
//        $enclosureBuilderService = self::$kernel->getContainer()
//            ->get('test.'.EnclosureBuilderService::class);

        $mockDinoFactory = $this->createMock(DinosaurFactory::class);
        $mockDinoFactory->expects($this->any())
            ->method('growFromSpecification')
            ->willReturnCallback(function($spec) {
                return new Dinosaur($spec);
            });

        $enclosureBuilderService = new EnclosureBuilderService(
            $this->getEntityManager(),
            $mockDinoFactory
        );

        $enclosureBuilderService->buildEnclosure();

        $em = $this->getEntityManager();

        $countSec = (int) $em->getRepository(Security::class)
            ->createQueryBuilder('s')
            ->select('COUNT(s.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $this->assertSame(1, $countSec, 'security not the same.');

        $countDino = (int) $em->getRepository(Dinosaur::class)
            ->createQueryBuilder('d')
            ->select('COUNT(d.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $this->assertSame(3, $countDino, 'dinosaur not the same.');


    }

    // just copy this method! :)
    private function truncateEntities()
    {
        $purger = new ORMPurger($this->getEntityManager());
        $purger->purge();

    }

    /**
     * @return EntityManager
     */
    private function getEntityManager()
    {
        return self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }


}
