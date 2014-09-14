<?php

namespace Zoop\User;

/**
 * Container for all User events.
 *
 * This class cannot be instantiated.
 * 
 * @author      Josh Stuart <josh.stuart@zoopcommerce.com>
 */
final class Events
{
    private function __construct() {}

    /**
     * The userOnAuth event occurs once we have authenticated a user
     * 
     * @var string
     */
    const userPostAuth = 'userPostAuth';
}
