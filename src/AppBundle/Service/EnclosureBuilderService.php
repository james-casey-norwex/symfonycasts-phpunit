<?php

namespace AppBundle\Service;

use AppBundle\Entity\Enclosure;
use AppBundle\Entity\Security;
use AppBundle\Factory\DinosaurFactory;
use Doctrine\ORM\EntityManagerInterface;

class EnclosureBuilderService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var DinosaurFactory
     */
    private $dinosaurFactory;

    /**
     * EnclosureBuilderService constructor.
     * @param EntityManagerInterface $entityManager
     * @param DinosaurFactory $dinosaurFactory
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        DinosaurFactory $dinosaurFactory
    )
    {
        $this->entityManager = $entityManager;
        $this->dinosaurFactory = $dinosaurFactory;
    }

    /**
     * @param int $numberOfSecuritySystems
     * @param int $numberOfDinosaurs
     * @return Enclosure
     * @throws \AppBundle\Exception\DinosaursAreRunningRampantException
     * @throws \AppBundle\Exception\NotABuffetException
     */
    public function buildEnclosure(
        int $numberOfSecuritySystems = 1,
        int $numberOfDinosaurs = 3
    ): Enclosure
    {
        $enclosure = new Enclosure();

        $this->addSecuritySystems($numberOfSecuritySystems, $enclosure);

        $this->addDinosaurs($numberOfDinosaurs, $enclosure);

        $this->entityManager->persist($enclosure);

        $this->entityManager->flush();

        return $enclosure;
    }


    /**
     * @param int $numberOfSecuritySystems
     * @param Enclosure $enclosure
     */
    private function addSecuritySystems(int $numberOfSecuritySystems, Enclosure $enclosure)
    {
        for ($i = 0; $i < $numberOfSecuritySystems; $i++) {
            $securityName = array_rand(['Fence', 'Electric fence', 'Guard tower']);
            $security = new Security($securityName, true, $enclosure);

            $enclosure->addSecurity($security);
        }
    }

    /**
     * @param int $numberOfDinosaurs
     * @param Enclosure $enclosure
     * @throws \AppBundle\Exception\DinosaursAreRunningRampantException
     * @throws \AppBundle\Exception\NotABuffetException
     */
    private function addDinosaurs(int $numberOfDinosaurs, Enclosure $enclosure)
    {
        for ($i = 0; $i < $numberOfDinosaurs; $i++) {
            $length = array_rand(['small', 'large', 'huge']);
            $diet = array_rand(['herbivore', 'carnivorous']);
            $specification = "{$length} {$diet} dinosaur";
            $dinosaur = $this->dinosaurFactory->growFromSpecification($specification);

            $enclosure->addDinosaur($dinosaur);
        }
    }
}
