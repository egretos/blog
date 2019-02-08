<?php

namespace App\Entity;

use App\Entity\Auth\Token;
use GraphAware\Neo4j\OGM\Annotations as OGM;

/**
 * Class User
 * @package App\Entity
 * @OGM\Node(label="User")
 */
class User
{
    /**
     * @var int
     * @OGM\GraphId()
     */
    public $id;

    /**
     * @var string
     * @OGM\Property(type="string")
     */
    public $name;

    /**
     * @var string
     * @OGM\Property(type="string")
     */
    public $email;

    /**
     * @var string
     * @OGM\Property(type="string")
     */
    public $password;

    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }
}