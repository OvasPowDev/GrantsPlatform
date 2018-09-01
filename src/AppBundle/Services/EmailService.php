<?php

namespace AppBundle\Services;

use AppBundle\Entity\Company;
use AppBundle\Entity\CreditRequest;
use AppBundle\Entity\Customer;
use AppBundle\Entity\CustomerRentContract;
use AppBundle\Entity\DeviceComponent;
use AppBundle\Entity\Media;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\User;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Notification;
use AppBundle\Entity\NotificationType;
use AppBundle\Entity\NotificationChannel;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class NotificationsService
 * @package AppBundle\Services
 */
class EmailService
{
    /** @var ContainerInterface $container */
    private $container;
    /** @var EntityManager $entityManager */
    private $entityManager;

    /**
     * ToolsService constructor.
     * @param ContainerInterface $serviceContainer
    //     * @param Router $router
     */
    public function __construct(ContainerInterface $serviceContainer)
    {
        $this->container = $serviceContainer;
        $this->entityManager = $this->container->get('doctrine')->getManager();
    }

    /**
     * Notify user that attendant has been approved.
     * @param String $message
     * @param String $subject
     * @param User $user
     *
     * @return void
     * */
    public function sendMail(User $user, $message, $subject)
    {
        /** @var Company $company */
        $company = $user->getCompany();


        if ($company->getEmail()) {
            $twig = $this->container->get('twig');

            $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($company->getEmail())
                ->setTo($user->getEmail())
                ->setBody(
                    $twig->render(':Emails:send-mail.html.twig',
                        ['subject' => $subject,
                            'text' => $message]
                    ), 'text/html'
                )->addPart(
                    $twig->render(':Emails:send-mail.txt.twig',
                        ['subject' => $subject,
                            'text' => $message]
                    )
                );
            $this->container->get('mailer')->send($message);
        }
    }

    /**
     * forgot password.
     * @param String $newPassword
     * @param User $user
     *
     * @return void
     * */
    public function sendMailForgotPassword(User $user, $newPassword)
    {
        /** @var Company $company */
        $company = $user->getCompany();
        $tools = $this->container->get('app.tools');
        $url = $tools->get('url', null, $company);
        if ($company->getEmail()) {
            $twig = $this->container->get('twig');

            $message = \Swift_Message::newInstance()
                ->setSubject('Restablecimiento de contraseña')
                ->setFrom($company->getEmail())
                ->setTo($user->getEmail())
                ->setBody(
                    $twig->render(':Emails:send-password.html.twig',
                        ['user' => $user,
                            'password' => $newPassword,
                            'company' => $company,
                            'url' => $url]
                    ), 'text/html'
                );
            $this->container->get('mailer')->send($message);
        }

    }

    /**
     * send email valid.
     * @param String $password
     * @param User $user
     *
     * @return void
     * */
    public function sendMailValid(User $user, $password)
    {
        $twig = $this->container->get('twig');

        /** @var Company $company */
        $company = $user->getCompany();
        $tools = $this->container->get('app.tools');
        $url = $tools->get('url', null, $company);
        $activeUrl = $url . 'active/'.$user->getId();

        $message = \Swift_Message::newInstance()
            ->setSubject('Validación de correo')
            ->setFrom($company->getEmail())
            ->setTo($user->getEmail())
            ->setBody(
                $twig->render(':Emails:send-valid-email.html.twig',
                    ['user' => $user,
                        'password' => $password,
                        'activeUrl' => $activeUrl,
                        'url' => $url]
                ), 'text/html'
            );
        $this->container->get('mailer')->send($message);
    }


    /**
     * @param CreditRequest $creditRequest
     */
    public function sendMailCreditRequestValid(CreditRequest $creditRequest)
    {
        $twig = $this->container->get('twig');
        $request = $creditRequest->getRequest();
        $customer = $request->getCustomer();
        $operator = $creditRequest->getBankOperator();
        /** @var User $user */
        $user = $customer->getUser();
        /** @var Company $company */
        $company = $user->getCompany();
        $tools = $this->container->get('app.tools');
        $url = $tools->get('url', null, $company);

        $message = \Swift_Message::newInstance()
            ->setSubject('Solicitud de crédito')
            ->setFrom($company->getEmail())
            ->setTo($operator->getEmail())
            ->setBody(
                $twig->render(':Emails:send-credit-request-email.html.twig',
                    ['user' => $user,
                        'noIdentification' => $customer->getIdentificationDocument(),
                        'maximumPrice' => $request->getMaximumPrice(),
                        'category' => $request->getCategory()->getName(),
                        'url' => $url]
                ), 'text/html'
            );
        $this->container->get('mailer')->send($message);
    }
}