<?php

namespace App\Repository\Auth;

use App\Repository\BaseNeo4jRepository;
use App\Entity\User;

/**
 * Class UserRepository
 * @package App\Repository\Auth
 */
class UserRepository extends BaseNeo4jRepository implements IUserRepository
{
    /**
     * @inheritdoc
     */
    public function emailExists(string $email)
    {
        return (bool) $this->getByEmail($email);
    }

    /**
     * @inheritdoc
     */
    public function getByEmail(string $email)
    {
        return $this
            ->entityManager
            ->getRepository(User::class)
            ->findOneBy(['email' => $email]);
    }

    /**
     * @inheritdoc
     */
    public function create(User $user)
    {
        $this
            ->entityManager
            ->persist($user);

        $this
            ->entityManager
            ->flush();
    }
}