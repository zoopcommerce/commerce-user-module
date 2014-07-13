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
     * @param \Zend\EventManager\Event $event
     */
    public function onBootstrap(MvcEvent $event)
    {
        $application = $event->getTarget();
        $serviceManager = $application->getServiceManager();
        
        $manifest = $serviceManager->get('shard.commerce.manifest');
        $shardSm = $manifest->getServiceManager();
        $sysUser = new System;
        $sysUser->addRole(Roles::SYSTEM_AUTH_USER);
        $shardSm->setService('user', $sysUser);
    }
    
    /**
     *
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../../config/module.config.php';
    }
}
