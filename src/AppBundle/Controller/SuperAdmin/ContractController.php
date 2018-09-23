<?php

namespace AppBundle\Controller\SuperAdmin;

use AppBundle\Entity\Company;
use AppBundle\Entity\Contract;
use AppBundle\Form\Admin\ContractType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/administrator/contract")
 * */
class ContractController extends Controller
{
    /**
     * Company list
     * @return Response
     * @Route("/", name="administrator_contract_list")
     * */
    public function indexAction()
    {
        $manager = $this->getDoctrine()->getManager();
        $opts = [];
        $contracts = $manager->getRepository('AppBundle:Contract')
            ->findAll();
        $opts['contracts'] = $contracts;
        rsort($contracts);
        return $this->render('SuperAdmin/Contract/index.html.twig', $opts);
    }

    /**
     * Create new contract entity.
     * @param Request $request
     * @return Response
     * @Route("/new", name="administrator_contract_new")
     * */
    public function newAction(Request $request)
    {
        $contract = new Contract();
        $contract->setActive(true);
        $contract->setUsedUsers(0);
        $contract->setGrantsUsed(0);
        $form = $this->createForm(ContractType::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->updateActiveContract($contract->getCompany());
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($contract);
            $manager->flush();
            $translator = $this->get('translator');
            $this->addFlash('success', $translator->trans('app.contract.new.success', [], 'AppBundle'));

            return $this->redirectToRoute('administrator_contract_list');
        }

        return $this->render('SuperAdmin/Contract/new.html.twig', ['form' => $form->createView()]);
    }

    private function updateActiveContract(Company $company){
        $manager = $this->getDoctrine()->getManager();
        $allContractCompany = $manager->getRepository('AppBundle:Contract')
            ->findBy(['company' => $company]);

        /** @var Contract $contract */
        foreach ($allContractCompany as $contract){
            $contract->setActive(false);
            $manager->persist($contract);
            $manager->flush();
        }
    }

    /**
     * Edit Contract entity
     * @param Request $request
     * @param Contract $contract
     * @return Response
     * @Route("/{id}/edit", name="administrator_contract_edit")
     * */
    public function editAction(Request $request, Contract $contract)
    {
        $translator = $this->get('translator');
        $form = $this->createForm(ContractType::class, $contract);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $validContract = $contract->getPlan();

            if ($validContract->getQuantityUser() < $contract->getUsedUsers()){
                $this->addFlash('warning', $translator->trans('app.administrator.valid.plan_not_valid', [], 'AppBundle'));
                return $this->render('SuperAdmin/Contract/edit.html.twig', ['form' => $form->createView(), 'plan' => $contract]);
            }

            if ($validContract->getQuantityGrant() < $contract->getGrantsUsed()){
                $this->addFlash('warning', $translator->trans('app.administrator.valid.plan_not_valid_grant', [], 'AppBundle'));
                return $this->render('SuperAdmin/Contract/edit.html.twig', ['form' => $form->createView(), 'plan' => $contract]);
            }
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($contract);
            $manager->flush();
            $translator = $this->get('translator');
            $this->addFlash('success', $translator->trans('app.contract.edit.success', [], 'AppBundle'));

            return $this->redirectToRoute('administrator_contract_list');
        }

        return $this->render('SuperAdmin/Contract/edit.html.twig', ['form' => $form->createView(), 'plan' => $contract]);
    }


    /**
     * Change active contract
     * @param Contract $contract
     * @return Response
     * @Route("/{id}/change_active", name="plan_contract_active")
     */
    public function changeActiveAction(Contract $contract)
    {
        if (!$contract->getActive()){
            $this->updateActiveContract($contract->getCompany());
        }
        $entityManager = $this->getDoctrine()->getManager();
        $contract->setActive(!$contract->getActive());
        $entityManager->flush();


        return $this->redirectToRoute('administrator_contract_list');
    }
}
