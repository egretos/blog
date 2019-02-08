<?php

namespace App\Repository\Auth;

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

    /**
     * creates user
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user);
}