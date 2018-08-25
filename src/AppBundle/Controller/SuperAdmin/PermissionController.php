<?php

namespace AppBundle\Controller\SuperAdmin;

use AppBundle\Entity\User;
use AppBundle\Entity\Permission;
use AppBundle\Security\PrimaryVoter;
use AppBundle\Form\SuperAdmin\PermissionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/administrator/permission")
 * */
class PermissionController extends Controller
{
    /**
     * Permission list
     * @return Response
     * @Route("/", name="administrator_permission_list")
     * */
    public function indexAction()
    {
        $this->denyAccessUnlessGranted(PrimaryVoter::INDEX, Permission::class);

        $manager = $this->getDoctrine()->getManager();
        $opts = [];

        $permissions = $manager->getRepository('AppBundle:Permission')
            ->findAll();

        $opts['permissions'] = $permissions;

        return $this->render('SuperAdmin/Permission/index.html.twig', $opts);
    }

    /**
     * Create new permission entity.
     * @param Request $request
     * @return Response
     * @Route("/new", name="administrator_permission_new")
     * */
    public function newAction(Request $request)
    {
        $permission = new Permission();

        $this->denyAccessUnlessGranted(PrimaryVoter::CREATE, $permission);

        $form = $this->createForm(PermissionType::class, $permission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($permission);
            $manager->flush();

            $translator = $this->get('translator');
            $this->addFlash('success', $translator->trans('app.permission.new.success', [], 'AppBundle'));

            return $this->redirectToRoute('administrator_permission_list');
        }

        return $this->render('SuperAdmin/Permission/new.html.twig', ['form' => $form->createView(), 'permission' => false]);
    }

    /**
     * Edit Permission entity
     * @param Request $request
     * @param Permission $permission Permission entity
     * @return Response
     * @Route("/{id}/edit", name="administrator_permission_edit")
     * */
    public function editAction(Request $request, Permission $permission)
    {
        $this->denyAccessUnlessGranted(PrimaryVoter::UPDATE, $permission);

        $form = $this->createForm(PermissionType::class, $permission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($permission);
            $manager->flush();

            $translator = $this->get('translator');
            $this->addFlash('success', $translator->trans('app.permission.edit.success', [], 'AppBundle'));

            return $this->redirectToRoute('administrator_permission_list');
        }

        return $this->render('SuperAdmin/Permission/edit.html.twig', ['form' => $form->createView(), 'permission' => $permission]);
    }

    /**
     * Remove permission entity.
     * @param Permission $permission Permission entity
     * @return Response
     * @Route("/{id}/delete", name="administrator_permission_delete", methods={"DELETE"})
     * */
    public function deleteAction(Permission $permission)
    {
        $this->denyAccessUnlessGranted(PrimaryVoter::DELETE, $permission);

        $translator = $this->get('translator');
        $manager = $this->getDoctrine()->getManager();

        $manager->remove($permission);

        try {
            $this->addFlash('success', $translator->trans('app.permission.remove.success', [], 'AppBundle'));
            $manager->flush();
        } catch (\Exception $e) {
            $this->addFlash('danger', $translator->trans('app.permission.remove.error', [], 'AppBundle'));
        }

        return $this->redirectToRoute('administrator_permission_list');
    }

    /**
     * Assign permission to ROLE
     * @return Response
     * @Route("/role", name="administrator_permission_assign", methods={"GET", "POST"})
     * */
    public function assignAction()
    {
        $this->denyAccessUnlessGranted(PrimaryVoter::ASSIGN, Permission::class);

        $entityManager = $this->getDoctrine()->getManager();
        $opts = [];

        $permissions = $entityManager->getRepository('AppBundle:Permission')
            ->findAll();

        $roles = User::ROLES_DEFINITION;

        $assignments = $entityManager->getRepository('AppBundle:RolePermission')
            ->findBy([], ['role' => 'ASC']);

        $opts['permissions'] = $permissions;
        $opts['roles'] = $roles;
        $opts['assignments'] = $assignments;

        return $this->render('SuperAdmin/Permission/assign.html.twig', $opts);
    }
}
