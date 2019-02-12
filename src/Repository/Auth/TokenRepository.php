<?php

namespace App\Repository\Auth;

use App\Entity\Auth\Token;
use App\Entity\Auth\User;
use App\Repository\BaseNeo4jRepository;

class TokenRepository extends BaseNeo4jRepository
{
    /**
     * @param Token $token
     * @param User|null $user
     * @return bool
     */
    public function create(Token $token, User $user = null)
    {
        if ($user) {
            $token->users()->add($user);
        }
        $this->update($token);

        return true;
    }

    /**
     * @param Token $token
     * @throws
     */
    public function update(Token $token)
    {
        $this->entityManager->persist($token);
        $this->entityManager->flush();
    }
}