<?php
/**
 * @package Zoop
 */
namespace Zoop\User\Crypt;

use Zoop\Common\Crypt\SaltInterface;

/**
 * @author Josh Stuart <josh.stuart@zoopcommerce.com>
 */
class Salt implements SaltInterface
{
    protected $salt;

    public function __construct($salt)
    {
        $this->salt = $salt;
    }

    public function getSalt()
    {
        return $this->salt;
    }
}
