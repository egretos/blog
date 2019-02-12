<?php

namespace App\Entity\Auth;

use App\Entity\Model;
use GraphAware\Neo4j\OGM\Annotations as OGM;
use GraphAware\Neo4j\OGM\Common\Collection;

/**
 * Class Token
 * @package App\Entity\Auth
 * @OGM\Node(label="Token")
 *
 * @property integer $id
 * @property string $resource
 * @property string $data
 * @property integer $expireAt
 * @property string $type;
 */
class Token extends Model
{
    const TYPE_AUTH = 'auth';
    const TYPE_API = 'api';

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
     * @var integer|bool
     * @OGM\Property(type="int")
     */
    public $expireAt = false;

    /**
     * @var string
     * @OGM\Property(type="string")
     */
    public $type = self::TYPE_AUTH;

    /**
     * @var User[]|Collection
     * @OGM\Relationship(type="Has", direction="INCOMING", mappedBy="tokens", collection=true, targetEntity="User")
     */
    protected $users;

    public function __construct()
    {
        $this->users = new Collection;
    }

    /**
     * @return User[]|Collection
     */
    public function users()
    {
        return $this->users;
    }

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

    public function isExpired()
    {
        return $this->expireAt && ($this->expireAt < time());
    }

    public function __toString()
    {
        return (string) $this->data;
    }
}