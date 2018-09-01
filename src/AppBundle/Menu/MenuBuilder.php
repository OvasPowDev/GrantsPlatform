<?php

namespace AppBundle\Menu;

use AppBundle\Entity\Company;
use AppBundle\Entity\Permission;
use AppBundle\Entity\User;
use AppBundle\Security\PrimaryVoter;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MenuBuilder
{
	/**
	 * @var FactoryInterface $factory
	 */
	private $factory;

	/**
	 * @var ContainerInterface $container
	 */
	private $container;

	/**
	 * @param FactoryInterface $factory
	 * @param ContainerInterface $container
	 *
	 * Add any other dependency you need
	 */
	public function __construct(FactoryInterface $factory, ContainerInterface $container)
	{
		$this->factory = $factory;
		$this->container = $container;
	}

	public function mainMenu(array $options)
    {
		$menu = $this->factory->createItem('root')
            ->setChildrenAttribute('class', 'nav metismenu')
            ->setChildrenAttribute('id', 'side-menu');

        $this->configureDashboardMenu($menu);
        $this->configureSuperAdministratorMenu($menu);
        $this->configureAdministratorMenu($menu);
//        $this->configureAPPMenu($menu);
        $this->configureUsersMenu($menu);
        $this->configureSettingsMenu($menu);
        $this->configureDocumentationMenu($menu);

        return $menu;
    }

    /**
     * @param $menu
     */
    public function configureDashboardMenu(ItemInterface &$menu)
    {
        $checker = $this->container->get('security.authorization_checker');

        if ($checker->isGranted(User::ROLE_ADMIN)) {
            $menu
                ->addChild('app.app.dashboard', ['route' => 'administrator_dashboard'])
                ->setAttribute('icon', 'fa fa-th-large');
        }

        if ($checker->isGranted(User::ROLE_APP)) {
            $menu
                ->addChild('app.app.dashboard', ['route' => 'app_homepage'])
                ->setAttribute('icon', 'fa fa-th-large');
        }
    }

    /**
     * @param $menu
     */
    public function configureDocumentationMenu(ItemInterface &$menu)
    {
        $checker = $this->container->get('security.authorization_checker');

        if ($checker->isGranted(User::ROLE_ADMIN)) {
            $menu
                ->addChild('app.api.docs', ['route' => 'nelmio_api_doc_index', 'routeParameters' => ['view' => 'admin']])
                ->setLinkAttribute('target', '_blank')
                ->setAttribute('class', 'special_link')
                ->setAttribute('icon', 'fa fa-book')
                ->setAttribute('tag', 'app.docs');
        }

        if ($checker->isGranted(User::ROLE_SUPER_ADMIN)) {
            $menu
                ->addChild('app.api.docs', ['route' => 'nelmio_api_doc_index', 'routeParameters' => ['view' => 'super_admin']])
                ->setLinkAttribute('target', '_blank')
                ->setAttribute('class', 'special_link')
                ->setAttribute('icon', 'fa fa-book')
                ->setAttribute('tag', 'app.docs');
        }

        if ($checker->isGranted(User::ROLE_APP)) {
            $menu
                ->addChild('app.api.docs', ['route' => 'nelmio_api_doc_index', 'routeParameters' => ['view' => 'app']])
                ->setLinkAttribute('target', '_blank')
                ->setAttribute('class', 'special_link')
                ->setAttribute('icon', 'fa fa-book')
                ->setAttribute('tag', 'app.docs');
        }
    }

    /**
     * @param $menu
     */
    public function configureAdministratorMenu(ItemInterface &$menu)
    {
    }

    /**
     * @param $menu
     */
    public function configureSuperAdministratorMenu(ItemInterface &$menu)
    {
        $checker = $this->container->get('security.authorization_checker');

        // Company menu
        if ($checker->isGranted(PrimaryVoter::MANAGE, Company::class)) {
            $menu
                ->addChild('app.administrator.companies', ['uri' => '#'])
                ->setAttribute('dropdown', true)
                ->setAttribute('icon', 'fa fa-building-o')
                ->setChildrenAttribute('class', 'nav nav-second-level collapse');

            if ($checker->isGranted(PrimaryVoter::CREATE, Company::class)) {
                $menu['app.administrator.companies']
                    ->addChild('app.administrator.companies.new', ['route' => 'administrator_company_new']);
            }

            if ($checker->isGranted(PrimaryVoter::INDEX, Company::class)) {
                $menu['app.administrator.companies']
                    ->addChild('app.administrator.companies.list', ['route' => 'administrator_company_list'])
                    ->setExtra('routes', ['administrator_company_list', 'administrator_company_edit']);
            }
        }

//        // Permission list
//        if ($checker->isGranted(PrimaryVoter::MANAGE, Permission::class)) {
//            $menu
//                ->addChild('app.administrator.permissions', ['uri' => '#'])
//                ->setAttribute('dropdown', true)
//                ->setAttribute('icon', 'fa fa-lock')
//                ->setChildrenAttribute('class', 'nav nav-second-level collapse');
//
//            if ($checker->isGranted(PrimaryVoter::CREATE, Permission::class)) {
//                $menu['app.administrator.permissions']
//                    ->addChild('app.administrator.permissions.new', ['route' => 'administrator_permission_new']);
//            }
//
//            if ($checker->isGranted(PrimaryVoter::INDEX, Permission::class)) {
//                $menu['app.administrator.permissions']
//                    ->addChild('app.administrator.permissions.list', ['route' => 'administrator_permission_list'])
//                    ->setExtra('routes', ['administrator_permission_list', 'administrator_permission_edit']);
//            }
//
//            if ($checker->isGranted(PrimaryVoter::ASSIGN, Permission::class)) {
//                $menu['app.administrator.permissions']
//                    ->addChild('app.administrator.permissions.assignment', ['route' => 'administrator_permission_assign'])
//                    ->setExtra('routes', ['administrator_permission_assign']);
//            }
//        }
    }

    /**
     * @param $menu
     */
    public function configureAPPMenu(ItemInterface &$menu)
    {
        $checker = $this->container->get('security.authorization_checker');

        if ($checker->isGranted(User::ROLE_APP)) {
            // TODO: Build menu
        }
    }

    /**
     * @param $menu
     */
    public function configureSettingsMenu(ItemInterface &$menu)
    {
        $checker = $this->container->get('security.authorization_checker');

        if ($checker->isGranted(User::ROLE_ADMIN)) {
            $menu
                ->addChild('app.settings', ['route' => 'administrator_settings'])
                ->setAttribute('icon', 'fa fa-cogs');
        }

        if ($checker->isGranted(User::ROLE_TRANSLATOR)) {
            $menu
                ->addChild('app.translate', ['route' => 'lexik_translation_overview'])
                ->setAttribute('icon', 'fa fa-language')
                ->setLinkAttribute('target', '_blank');
        }
    }

    /**
     * @param $menu
     */
    public function configureUsersMenu(ItemInterface &$menu)
    {
        $checker = $this->container->get('security.authorization_checker');

        if ($checker->isGranted(PrimaryVoter::MANAGE, User::class)) {
            $menu
                ->addChild('app.users', ['uri' => '#'])
                ->setAttribute('dropdown', true)
                ->setAttribute('icon', 'fa fa-group')
                ->setChildrenAttribute('class', 'nav nav-second-level collapse');

            $menu['app.users']
                ->addChild('app.administrator.users.new', ['route' => 'administrator_user_new']);

            $menu['app.users']
                ->addChild('app.administrator.users.list', ['route' => 'administrator_user_list'])
                ->setExtra('routes', ['administrator_user_list', 'administrator_user_edit', 'app_user_profile']);
        }
    }
}
