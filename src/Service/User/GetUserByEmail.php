<?php

namespace App\Service\User;

use App\Entity\User;
use App\Model\User\Exception\ResourceNotFoundException;
use App\Model\User\Exception\UserNotFound;
use App\Repository\UserRepository;


class GetUserByEmail
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(string $email): User
    {
        $user = $this->userRepository->findOneByEmail($email);

        if (!$email) {
            ResourceNotFoundException::createFromResourceAndProperty(User::class, 'email');
        }
        return $user;
    }
}
