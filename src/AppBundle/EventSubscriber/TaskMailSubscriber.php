<?php

// src/AppBundle/EventSubscriber/TaskMailSubscriber.php

namespace AppBundle\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use AppBundle\Entity\Task;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class TaskMailSubscriber implements EventSubscriberInterface
{
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [['sendMail', EventPriorities::POST_WRITE]],
        ];
    }

    public function sendMail(GetResponseForControllerResultEvent $event)
    {
        $task = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$task instanceof Task || Request::METHOD_POST !== $method) {
            return;
        }

        $message = \Swift_Message::newInstance()
            ->setSubject('A new task has been added')
            ->setFrom('system@example.com')
            ->setTo('contact@no-reply.com')
            ->setBody(sprintf('The task #%d has been added.', $task->getId()));

        $this->mailer->send($message);
    }
}