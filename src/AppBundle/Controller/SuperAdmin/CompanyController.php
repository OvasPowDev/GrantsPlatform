<?php

namespace AppBundle\Controller\SuperAdmin;

use AppBundle\Entity\Company;
use AppBundle\Form\Admin\CompanyType;
use AppBundle\Security\PrimaryVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/administrator/company")
 * */
class CompanyController extends Controller
{
    /**
     * Company list
     * @return Response
     * @Route("/", name="administrator_company_list")
     * */
    public function indexAction()
    {
        $this->denyAccessUnlessGranted(PrimaryVoter::INDEX, Company::class);

        $manager = $this->getDoctrine()->getManager();
        $opts = [];

        $companies = $manager->getRepository('AppBundle:Company')
            ->findAll();

        $opts['companies'] = $companies;

        return $this->render('SuperAdmin/Company/index.html.twig', $opts);
    }

    /**
     * Create new company entity.
     * @param Request $request
     * @return Response
     * @Route("/new", name="administrator_company_new")
     * */
    public function newAction(Request $request)
    {
        $company = new Company();
        $company->setEnabled(true);

        $this->denyAccessUnlessGranted(PrimaryVoter::CREATE, $company);

        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($company);
            $manager->flush();

            $translator = $this->get('translator');
            $this->addFlash('success', $translator->trans('app.company.new.success', [], 'AppBundle'));

            return $this->redirectToRoute('administrator_company_list');
        }

        return $this->render('SuperAdmin/Company/new.html.twig', ['form' => $form->createView(), 'company' => false]);
    }

    /**
     * Edit Company entity
     * @param Request $request
     * @param Company $company Company entity
     * @return Response
     * @Route("/{id}/edit", name="administrator_company_edit")
     * */
    public function editAction(Request $request, Company $company)
    {
        $this->denyAccessUnlessGranted(PrimaryVoter::UPDATE, $company);

        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($company);
            $manager->flush();

            $translator = $this->get('translator');
            $this->addFlash('success', $translator->trans('app.company.edit.success', [], 'AppBundle'));

            return $this->redirectToRoute('administrator_company_list');
        }

        return $this->render('SuperAdmin/Company/edit.html.twig', ['form' => $form->createView(), 'company' => $company]);
    }

    /**
     * Remove company entity.
     * @param Company $company Company entity
     * @return Response
     * @Route("/{id}/delete", name="administrator_company_delete", methods={"DELETE"})
     * */
    public function deleteAction(Company $company)
    {
        $this->denyAccessUnlessGranted(PrimaryVoter::DELETE, $company);

        $translator = $this->get('translator');
        $manager = $this->getDoctrine()->getManager();

        $manager->remove($company);

        try {
            $this->addFlash('success', $translator->trans('app.company.remove.success', [], 'AppBundle'));
            $manager->flush();
        } catch (\Exception $e) {
            $this->addFlash('danger', $translator->trans('app.company.remove.error', [], 'AppBundle'));
        }

        return $this->redirectToRoute('administrator_company_list');
    }
}
