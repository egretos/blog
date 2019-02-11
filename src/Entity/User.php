<?php

namespace App\Entity;

use App\Entity\Auth\Token;
use GraphAware\Neo4j\OGM\Annotations as OGM;

/**
 * Class User
 * @package App\Entity
 * @OGM\Node(label="User")
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $password
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

    protected $token = false;

    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }

    /**
     * @return bool|Token
     */
    public function getToken()
    {
        return $this->token;
    }

    public function setToken(Token $token)
    {
        $this->token = $token;
        return $this;
    }

    public function hasAuthToken()
    {
        return $this->token instanceof Token;
    }
}