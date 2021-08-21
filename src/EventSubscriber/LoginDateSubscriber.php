<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use App\Entity\UserTimestamps;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

/**
 * The listener that sets the "firstLoginDate" and "lastLoginDate" properties of the user making the request, if any.
 */
class LoginDateSubscriber implements EventSubscriberInterface
{
    public function __construct(Security $security, EntityManagerInterface $manager)
    {
        $this->security = $security;
        $this->manager = $manager;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['update', EventPriorities::PRE_RESPOND],
        ];
    }

    public function update(ViewEvent $event): void
    {
        // @var UserTimestamps $userTimestamps
        if (null !== $this->security->getUser()) {
            $userTimestamps = $this->security->getUser()->getTimestamps();
            if (null === $userTimestamps->getFirstLoginDate()) {
                $userTimestamps->setFirstLoginDate(new DateTime());
            }
            $userTimestamps->setLastLoginDate(new DateTime());

            $this->manager->persist($userTimestamps);
            $this->manager->flush();
        }
    }
}
