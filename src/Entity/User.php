<?php

namespace App\Entity;

use GraphAware\Neo4j\OGM\Annotations as OGM;

/**
 * @OGM\Node(label="User")
 */
class User
{
    /**
     * @var string
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
}