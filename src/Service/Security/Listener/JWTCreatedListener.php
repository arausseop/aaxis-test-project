<?php

declare(strict_types=1);

namespace App\Service\Security\Listener;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

final class JWTCreatedListener
{
    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        /** @var User $user */
        $user = $event->getUser();

        $payload = $event->getData();
        // $payload['roles'] = $user->getRoles();
        $payload['name'] = $user->getName();

        $event->setData($payload);
    }
}
