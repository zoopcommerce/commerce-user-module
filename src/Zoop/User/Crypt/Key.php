<?php
/**
 * @package Zoop
 */
namespace Zoop\User\Crypt;

use Zoop\Common\Crypt\KeyInterface;

/**
 * @author Josh Stuart <josh.stuart@zoopcommerce.com>
 */
class Key implements KeyInterface
{
    protected $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function getKey()
    {
        return $this->key;
    }
}
