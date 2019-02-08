<?php

namespace App\Repository\Auth;

use App\Entity\Auth\Token;
use App\Entity\User;
use App\Repository\BaseNeo4jRepository;

class TokenRepository extends BaseNeo4jRepository
{
    /**
     * @param Token $token
     * @param User|null $user
     *
     * @return bool
     */
    public function create(Token $token, User $user = null)
    {
        $query = $this
            ->entityManager
            ->createQuery('MATCH (n:Person) WHERE n.name CONTAINS {part} RETURN n LIMIT 10');


        $query->addEntityMapping('n', \Demo\Person::class);
        $query->setParameter('part', 'Tom');

        $this
            ->entityManager
            ->persist($token);

        $this
            ->entityManager
            ->flush();

        return true;
    }
}