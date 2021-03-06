<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Company;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadCompanyData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadCompanyData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /** @var ContainerAwareInterface $container */
    private $container;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $company = new Company();
        $company->setName('Expanse');
        $company->setEnabled(true);
        $company->setEmail('info@expanse.com');
        $company->setExpired(false);
        $company->setAbout(' ');
        $company->setNotes(' ');

        $manager->persist($company);
        $manager->flush();

        $this->addReference('expanse', $company);
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 10;
    }
}