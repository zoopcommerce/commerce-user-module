<?php

namespace Zoop\User\Service;

use Zend\Authentication\Adapter\Http;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\NonPersistent;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * 
 * @author Josh Stuart <josh.stuart@zoopcommerce.com>
 */
class AuthenticationServiceFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return AuthenticationService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $adapter = new Http([
            'accept_schemes' => 'basic',
            'realm' => 'zoop'
        ]);
        $adapter->setBasicResolver($serviceLocator->get('zoop.user.authentication.adapter.http'));
        $adapter->setRequest($serviceLocator->get('request'));
        $adapter->setResponse($serviceLocator->get('response'));

        return new AuthenticationService(new NonPersistent, $adapter);
    }
}
