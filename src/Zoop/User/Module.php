<?php

/**
 * @package Zoop
 */

namespace Zoop\User;

use Zend\Mvc\MvcEvent;
use Zoop\User\DataModel\System;
use Zoop\User\Roles;

/**
 *
 * @author  Josh Stuart <josh.stuart@zoopcommerce.com>
 */
class Module
{
    /**
     *
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../../config/module.config.php';
    }
}
