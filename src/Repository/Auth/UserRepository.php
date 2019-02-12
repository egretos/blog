<?php

namespace App\Repository\Auth;

use App\Entity\Auth\Token;
use App\Repository\BaseNeo4jRepository;
use App\Entity\Auth\User;
use GraphAware\Neo4j\OGM\Query;

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
        $query = $this
            ->entityManager
            ->createQuery('
            MATCH (user:User) WHERE user.email = {user_email}
            OPTIONAL MATCH (user)-[:Has]->(token:Token)
            RETURN user, collect(token) as tokens
            ');

        $query->setParameter('user_email', $email);
        $query->addEntityMapping('user', User::class);
        $query->addEntityMapping('tokens', Token::class, Query::HYDRATE_COLLECTION);

        $results = $query->getResult();
        $results = array_shift($results);

        /** @var User $user */
        $user = $results['user'];

        if($results['tokens']) {
            $user->setToken(array_shift($results['tokens']));
        }

        return $user;
    }

    /**
     * @inheritdoc
     *
     * @throws
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