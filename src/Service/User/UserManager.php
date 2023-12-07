<?php

namespace App\Service\User;

use App\Entity\User;
use App\Model\Exception\DatabaseException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserManager
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserRepository $userRepository,
    ) {
    }

    public function getRepository(): UserRepository
    {
        return $this->userRepository;
    }

    public function create(): User
    {
        $user = new User();
        return $user;
    }

    public function persist(User $user): User
    {
        $this->em->persist($user);
        return $user;
    }

    public function save(User $user): User
    {
        try {
            $this->em->persist($user);
            $this->em->flush();
        } catch (\Exception $e) {
            throw DatabaseException::createFromMessage($e->getMessage());
        }
        return $user;
    }

    public function reload(User $user): User
    {
        $this->em->refresh($user);
        return $user;
    }
}
