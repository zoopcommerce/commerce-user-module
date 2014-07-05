<?php

namespace Zoop\User\DataModel;

/**
 * @author  Josh Stuart <josh.stuart@zoopcommerce.com>
 */
interface UserInterface
{
    /**
     * @return string
     */
    public function getUsername();

    /**
     * @param string $name
     */
    public function setUsername($name);
}
