<?php

namespace App\EventSubscriber;

use App\Entity\UserTimestamps;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

/**
 * The listener that sets the `firstLoginDate` and `lastLoginDate` properties of the user making the request, if any. It is called before sending the response.
 *
 * @see https://api-platform.com/docs/core/events/
 */
class LoginDateSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly Security $security, private readonly EntityManagerInterface $manager)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => ['update'],
        ];
    }

    public function update(LoginSuccessEvent $event): void
    {
        if (null !== $this->security->getUser()) {
            /** @var UserTimestamps $userTimestamps */
            $userTimestamps = $this->security->getUser()->getTimestamps();
            if (null === $userTimestamps->getFirstLoginDate()) {
                $userTimestamps->setFirstLoginDate(new \DateTime());
            }

            $userTimestamps->setLastLoginDate(new \DateTime());

            $this->manager->persist($userTimestamps);
            $this->manager->flush();
        }
    }
}
