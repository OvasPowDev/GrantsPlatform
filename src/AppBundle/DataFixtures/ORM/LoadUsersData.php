<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadUserData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadUsersData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface $container
     */
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
        $userManager = $this->container->get('fos_user.user_manager');

        /** ----------------------------------- **/
        $super = $userManager->createUser();
        $super->setUsername('super');
        $super->setEmail('info@expanse.com');
        $super->setPlainPassword('826455');
        $super->setEnabled(true);
        $super->setCompany($this->getReference('expanse'));
        $super->setRoles(array('ROLE_SUPER_ADMIN', 'ROLE_TRANSLATOR'));
        $super->setApiKey($this->container->get('app.tools')->generateApiKey());
        $super->setLocale('es');
        $super->setFirstName('Expanse');
        $super->setLastName('');

        $userManager->updateUser($super, true);
        $this->addReference('super-user', $super);
        /** ----------------------------------- **/
        $admin = $userManager->createUser();
        $admin->setUsername('admin');
        $admin->setEmail('admin@expanse.com');
        $admin->setPlainPassword('826455');
        $admin->setEnabled(true);
        $admin->setCompany($this->getReference('expanse'));
        $admin->setRoles(array('ROLE_ADMIN', 'ROLE_TRANSLATOR'));
        $admin->setApiKey($this->container->get('app.tools')->generateApiKey());
        $admin->setLocale('es');
        $admin->setFirstName('Admin');
        $admin->setLastName('Expanse');

        $userManager->updateUser($admin, true);
        $this->addReference('admin-user', $admin);
        /** ----------------------------------- **/
        $app = $userManager->createUser();
        $app->setUsername('app');
        $app->setEmail('app@expanse.com');
        $app->setPlainPassword('826455');
        $app->setEnabled(true);
        $app->setCompany($this->getReference('expanse'));
        $app->setRoles(array('ROLE_APP', 'ROLE_TRANSLATOR'));
        $app->setApiKey($this->container->get('app.tools')->generateApiKey());
        $app->setLocale('es');
        $app->setFirstName('APP');
        $app->setLastName('Expanse');

        $userManager->updateUser($app, true);
        $this->addReference('app-user', $app);
        /** ----------------------------------- **/
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 15;
    }
}