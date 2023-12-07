<?php

namespace App\Service\User;

use App\Entity\User;
use App\Model\User\Exception\UserAlreadyExistsException;
use App\Repository\UserRepository;
use App\Service\Security\PasswordHasherInterface;

class CreateUserService
{
    public function __construct(
        private readonly UserManager $userManager,
        private readonly PasswordHasherInterface $passwordHasher
    ) {
    }

    public function create(string $name, string $email, string $password): array
    {

        if (null !== $this->userManager->getRepository()->findOneByEmail($email)) {
            throw UserAlreadyExistsException::createFromEmail($email);
        }

        $user = new User();
        $user->setName($name);
        $user->setEmail($email);

        $password = $this->passwordHasher->hashPasswordForUser($user, $password);
        $user->setPassword($password);

        $this->userManager->save($user);

        return [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ];
    }
}
