<?php

/**
 * @package    Zoop
 * @license    MIT
 */
namespace Zoop\User\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zoop\User\HttpBasicResolver;

/**
 * 
 * @author Josh Stuart <josh.stuart@zoopcommerce.com>
 */
class HttpBasicResolverFactory implements FactoryInterface
{
    /**
     *
     * @param  \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return \Zend\Authentication\AuthenticationService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $return = new HttpBasicResolver();

        return $return;
    }
}
