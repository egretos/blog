<?php

namespace App\Repository\Person;

use App\Entity\User;

interface IUserRepository
{
    /**
     * @param string $email
     * @return bool
     */
    public function emailExists(string $email);

    /**
     * @param string $email
     * @return User|null
     */
    public function getByEmail(string $email);
}