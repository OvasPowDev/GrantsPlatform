<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\User;
use AppBundle\Entity\Settings;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/administrator")
 * */
class SettingsController extends Controller
{
    /**
     * Save global settings.
     * @param Request $request
     * @return Response
     * @Route("/settings", name="administrator_settings")
     * */
    public function settingsAction(Request $request)
    {
        /** @var User $current_user */
        $current_user = $this->getUser();
        $company = $current_user->getCompany();

        $entityManager = $this->getDoctrine()->getManager();
        $settings = $entityManager->getRepository('AppBundle:Settings')
            ->findOneBy(['company' => $company]);

        if (!$settings) {
            $settings = new Settings();
            $settings->setCompany($company);
        }

        $form = $this->createForm('AppBundle\Form\Admin\SettingsType', $settings);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($settings);
            $entityManager->flush();

            $translator = $this->get('translator');
            $this->addFlash('success', $translator->trans('app.settings.edit.success', [], 'AppBundle'));

            return $this->redirectToRoute('grand_central');
        }

        return $this->render('Admin/Settings/edit.html.twig', ['form' => $form->createView()]);
    }
}
