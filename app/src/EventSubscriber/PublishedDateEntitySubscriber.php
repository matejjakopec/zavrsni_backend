<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\PublishedDateInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class PublishedDateEntitySubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return[
            KernelEvents::VIEW => ['setPublishedDate', EventPriorities::PRE_WRITE]
        ];
    }

    public function setPublishedDate(ViewEvent $event)
    {
        $entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();


        if(!$entity instanceof PublishedDateInterface
            || !in_array($method, [Request::METHOD_POST, Request::METHOD_PUT]))
        {
            return;
        }

        $entity->setPublished(new \DateTime());

    }

}