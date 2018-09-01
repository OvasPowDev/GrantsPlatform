<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseNullableUserEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller managing the resetting of the password.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Christophe Coevoet <stof@notk.org>
 */
class ResettingController extends Controller
{
    private $eventDispatcher;
    private $formFactory;
    private $userManager;
    private $tokenGenerator;
    private $mailer;

    /**
     * @var int
     */
    private $retryTtl;


    public function __construct()
    {
    }

    /**
     * Request reset user password: show form.
     */
    public function requestAction()
    {
        return $this->render('@FOSUser/Resetting/request.html.twig');
    }

    /**
     * Request reset user password: submit form and send email.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function sendEmailAction(Request $request)
    {
        $email = $request->get('username', null);
        $userManager = $this->get('fos_user.user_manager');

        /** @var User $user */
        $user = $userManager->findUserByUsernameOrEmail($email);

        try {
            if ($user) {
                $validate = self::isValidUser($user);
                if ($validate) {
                    $newPassword = $this->get('app.generator')->password(10);
                    $user->setPlainPassword($newPassword);
                    $user->setPasswordRequestedAt(new \DateTime());
                    $userManager->updateUser($user);
                    $this->get('app.email')->sendMailForgotPassword($user, $newPassword);

                } else {
                    return $this->render('@FOSUser/Resetting/request_error.html.twig');
                }

            } else {
                return $this->render('@FOSUser/Resetting/request_error.html.twig');
            }

        } catch (Exception $e) {
            return $this->render('@FOSUser/Resetting/request_error.html.twig');
        }

        return new RedirectResponse($this->generateUrl('fos_user_resetting_check_email', array('username' => $user->getUsername())));
    }

    /**
     * Validate If user exists, is enabled
     * @param User $user >
     * @param bool $enabled
     * @return array
     * @throws \Exception
     *
     * */
    private function isValidUser(User $user, $enabled = true)
    {
        $valid = false;
        $translator = $this->get('translator');
        $errors = [];
        $type = 'info';

        try {
            if ($user && $enabled) {
                if ($user->isEnabled()) {
                    $valid = true;
                    $type = 'info';
                } else {
                    $valid = false;
                    $type = 'error';
                    $errors[] = $translator->trans('app.api.valid.user_not_enabled', [], 'AppBundle');
                }
            } else {
                $errors[] = $translator->trans('app.api.valid.user_not_exists', [], 'AppBundle');
            }
        } catch (\Exception $e) {
            $errors[] = $e->getMessage();
            $type = 'error';
        }

        return ['valid' => $valid, 'errors' => $errors, 'type' => $type];
    }

    /**
     * Tell the user to check his email provider.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function checkEmailAction(Request $request)
    {
        $username = $request->query->get('username');

        if (empty($username)) {
            // the user does not come from the sendEmail action
            return new RedirectResponse($this->generateUrl('fos_user_resetting_request'));
        }

        return $this->render('@FOSUser/Resetting/check_email.html.twig', array(
            'tokenLifetime' => ceil($this->retryTtl / 3600),
        ));
    }

    /**
     * Reset user password.
     *
     * @param Request $request
     * @param string $token
     *
     * @return Response
     */
    public function resetAction(Request $request, $token)
    {
        $user = $this->userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            return new RedirectResponse($this->container->get('router')->generate('fos_user_security_login'));
        }

        $event = new GetResponseUserEvent($user, $request);
        $this->eventDispatcher->dispatch(FOSUserEvents::RESETTING_RESET_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $this->formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = new FormEvent($form, $request);
            $this->eventDispatcher->dispatch(FOSUserEvents::RESETTING_RESET_SUCCESS, $event);

            $this->userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_profile_show');
                $response = new RedirectResponse($url);
            }

            $this->eventDispatcher->dispatch(
                FOSUserEvents::RESETTING_RESET_COMPLETED,
                new FilterUserResponseEvent($user, $request, $response)
            );

            return $response;
        }

        return $this->render('@FOSUser/Resetting/reset.html.twig', array(
            'token' => $token,
            'form' => $form->createView(),
        ));
    }
}
