<?php

namespace App\Repository\Auth;

use App\Entity\Auth\Token;
use App\Entity\User;
use App\Repository\BaseNeo4jRepository;
use GraphAware\Common\Collection\Map;

class TokenRepository extends BaseNeo4jRepository
{
    /**
     * @param Token $token
     * @param User|null $user
     *
     * @throws
     * @return bool
     */
    public function create(Token $token, User $user = null)
    {
        $token->users()->add($user);
        $this->entityManager->persist($token);
        $this->entityManager->flush();
    }
}