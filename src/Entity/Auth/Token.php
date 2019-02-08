<?php

namespace App\Entity\Auth;

use App\Entity\User;
use GraphAware\Neo4j\OGM\Annotations as OGM;
use GraphAware\Neo4j\OGM\Common\Collection;

/**
 * Class Token
 * @package App\Entity\Auth
 * @OGM\Node(label="Token")
 */
class Token
{
    /**
     * @var integer
     * @OGM\GraphId()
     */
    public $id;

    /**
     * @var string
     * @OGM\Property(type="string")
     */
    public $resource = 'app';

    /**
     * @var string
     * @OGM\Property(type="string")
     */
    public $data;

    /**
     * @var integer
     * @OGM\Property(type="int")
     */
    public $expireAt;

    /**
     * @var string
     * @OGM\Property(type="string")
     */
    public $type = 'auth';

    /**
     * @param integer|bool $timeout
     * @return $this
     */
    public function generate($timeout = false)
    {
        try {
            $this->data = md5(random_bytes(64));
        } catch (\Exception $exception) {
            $this->data = md5(uniqid(mt_rand()));
        }

        if ($timeout) {
            $this->expireAt = $timeout;
        } else {
            $this->expireAt = strtotime('+1 hour');
        }

        return $this;
    }

    public function __toString()
    {
        return (string) $this->data;
    }

}