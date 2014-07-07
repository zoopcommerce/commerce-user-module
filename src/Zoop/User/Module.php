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
     *
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../../config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach(
            MvcEvent::EVENT_ROUTE,
            [$this, 'identifyRequest']
        );
    }

    public function identifyRequest(MvcEvent $e)
    {
        $application = $e->getApplication();
        $sm = $application->getServiceManager();
        
        //trigger the user factory
        $user = $sm->get('user');
    }

}
