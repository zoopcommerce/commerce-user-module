<?php

/**
 * @package Zoop
 */

namespace Zoop\User;

use Zend\Mvc\MvcEvent;

/**
 *
 * @author  Josh Stuart <josh.stuart@zoopcommerce.com>
 */
class Module
{
    /**
     * Adds a store filter listener
     *
     * @param \Zend\EventManager\Event $event
     */
    public function onBootstrap(MvcEvent $event)
    {
        $application = $event->getTarget();
        $serviceManager = $application->getServiceManager();

        //filter events
        $eventManager = $application->getEventManager();
        $eventManager->attach($serviceManager->get('zoop.commerce.user.entitylistener'));
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
