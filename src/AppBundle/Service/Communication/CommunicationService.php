<?php

namespace AppBundle\Service\Communication;

use AppBundle\Communication\Email\Message;
use AppBundle\Event\Communication\Email\EmailEvent;
use AppBundle\Event\Communication\Email\EmailSent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Templating\EngineInterface as Templating;
use Symfony\Component\Translation\TranslatorInterface as Translator;

class CommunicationService
{

    const ID = 'app.communication';

    /**
     *
     * @var EmailService
     */
    private $emailService;

    /**
     *
     * @var Templating
     */
    private $twigEngine;

    /**
     *
     * @var Translator 
     */
    private $translator;

    /**
     *
     * @var EventDispatcherInterface 
     */
    private $eventDispatcher;

    public function __construct(
            EmailService $emailService,
            Templating $twigEngine,
            Translator $translator,
            EventDispatcherInterface $eventDispatcher
    )
    {
        $this->emailService = $emailService;
        $this->twigEngine = $twigEngine;
        $this->translator = $translator;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function sendConfirmationEmail($emailAddress, $name, $orderNumber, $locale = 'en')
    {
        $arguments = array('customerName' => $name, 'orderNumber' => $orderNumber);
        $this->eventDispatcher->dispatch(
            EmailEvent::BEFORE_SEND, new EmailEvent('confirmation', $emailAddress, $arguments)
        );
        return $this->sendEmail('confirmation', $emailAddress, $locale, $arguments);
    }

    public function sendDeliveryEmail($emailAddress, $orderNumber)
    {
        
    }

    public function sendInvoice($emailAddress, $orderNumber)
    {
        
    }

    public function sendSatisfactionSurvey($emailAddress, $orderNumber)
    {
        
    }

    private function sendEmail($type, $emailAddress, $locale, $arguments)
    {
        $this->translator->setLocale($locale);
        $message = $this->constructEmailMessage($type, $emailAddress, $arguments);
        $status = $this->emailService->send($message);
        $this->eventDispatcher->dispatch(
                EmailEvent::SENT, new EmailSent($type, $emailAddress, $arguments, $message)
        );
        return $status;
    }

    private function renderTempalate($type, $arguments)
    {
        $templateName = sprintf('AppBundle:Templates:Email/%s.html.twig', $type);
        return $this->twigEngine->render($templateName, $arguments);
    }

    private function getSubject($type, $arguments)
    {
        $translationKey = sprintf('%s.subject', $type);
        return $this->translator->trans($translationKey, $arguments, 'email');
    }

    private function getFrom($type)
    {
        $translationKey = sprintf('%s.from', $type);
        return $this->translator->trans($translationKey, array(), 'email');
    }

    private function constructEmailMessage($type, $emailAddress, $arguments)
    {
        $message = new Message();
        $message->setTo($emailAddress);
        $message->setMessage($this->renderTempalate($type, $arguments));
        $message->setSubject($this->getSubject($type, $arguments));
        $message->setFrom($this->getFrom($type));
        return $message;
    }

}