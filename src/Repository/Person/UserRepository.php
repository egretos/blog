<?php

namespace App\Repository\Person;

use App\Entity\User;
use GraphAware\Neo4j\Client\ClientInterface;
use GraphAware\Neo4j\OGM\EntityManager;
use GraphAware\Neo4j\OGM\EntityManagerInterface;

/**
 * Class UserRepository
 * @package App\Repository\Person
 *
 * @property ClientInterface $client
 * @property EntityManager $entityManager
 */
class UserRepository implements IUserRepository
{
    private $client;

    public function __construct(ClientInterface $client, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->entityManager = $entityManager;
    }

    public function emailExists(string $email)
    {
        return (bool)$this->getByEmail($email);
    }

    public function getByEmail(string $email)
    {
        return $this
            ->entityManager
            ->getRepository(User::class)
            ->findOneBy(['email' => $email]);
    }
}