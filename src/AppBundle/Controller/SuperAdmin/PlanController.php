<?php

namespace AppBundle\Controller\SuperAdmin;

use AppBundle\Entity\Plan;
use AppBundle\Form\Admin\PlanType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/administrator/plan")
 * */
class PlanController extends Controller
{
    /**
     * Company list
     * @return Response
     * @Route("/", name="administrator_plan_list")
     * */
    public function indexAction()
    {
        $manager = $this->getDoctrine()->getManager();
        $opts = [];
        $companies = $manager->getRepository('AppBundle:Plan')
            ->findAll();
        $opts['plans'] = $companies;

        return $this->render('SuperAdmin/Plan/index.html.twig', $opts);
    }

    /**
     * Create new plan entity.
     * @param Request $request
     * @return Response
     * @Route("/new", name="administrator_plan_new")
     * */
    public function newAction(Request $request)
    {
        $plan = new Plan();
        $plan->setActive(true);
        $form = $this->createForm(PlanType::class, $plan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($plan);
            $manager->flush();
            $translator = $this->get('translator');
            $this->addFlash('success', $translator->trans('app.plan.new.success', [], 'AppBundle'));

            return $this->redirectToRoute('administrator_plan_list');
        }

        return $this->render('SuperAdmin/Plan/new.html.twig', ['form' => $form->createView(), 'company' => false]);
    }

    /**
     * Edit Plan entity
     * @param Request $request
     * @param Plan $plan
     * @return Response
     * @Route("/{id}/edit", name="administrator_plan_edit")
     * */
    public function editAction(Request $request, Plan $plan)
    {
        $form = $this->createForm(PlanType::class, $plan);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($plan);
            $manager->flush();
            $translator = $this->get('translator');
            $this->addFlash('success', $translator->trans('app.plan.edit.success', [], 'AppBundle'));

            return $this->redirectToRoute('administrator_plan_list');
        }

        return $this->render('SuperAdmin/Plan/edit.html.twig', ['form' => $form->createView(), 'plan' => $plan]);
    }

    /**
     * Change active plan
     * @param Plan $plan
     * @return Response
     * @Route("/{id}/change_active", name="plan_change_active")
     */
    public function changeActiveAction(Plan $plan)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $plan->setActive(!$plan->getActive());
        $entityManager->flush();

        return $this->redirectToRoute('administrator_plan_list');
    }
}
