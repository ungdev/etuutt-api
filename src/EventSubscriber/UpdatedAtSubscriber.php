<?php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * The listener is called before writing an entity into the database. If the entity has an `updatedAt` property, it sets its value to now.
 *
 * @see https://api-platform.com/docs/core/events/
 */
final class UpdatedAtSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['update', EventPriorities::PRE_WRITE],
        ];
    }

    public function update(ViewEvent $event): void
    {
        $entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        $methodSupported = \in_array($method, [Request::METHOD_POST, Request::METHOD_PUT, Request::METHOD_PATCH], true);
        if (!$methodSupported) {
            return;
        }
        $isUpdatedAtAble = property_exists($entity::class, 'updatedAt') || User::class === $entity::class;

        if ($isUpdatedAtAble) {
            $entity = User::class === $entity::class ? $entity->getTimestamps() : $entity;
            $entity->setUpdatedAt(new \DateTime());
        }
    }
}
