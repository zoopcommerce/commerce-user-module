<?php

/**
 * @package    Zoop
 * @license    MIT
 */
namespace Zoop\Sage\Service;

use Zend\Authentication\Adapter\Http;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\NonPersistent;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class AuthenticationServiceFactory implements FactoryInterface
{
    /**
     *
     * @param  \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return \Zend\Authentication\AuthenticationService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $adapter = new Http([
            'accept_schemes' => 'basic',
            'realm' => 'sage'
        ]);
        $adapter->setBasicResolver($serviceLocator->get('zoop.sage.authentication.adapter.http'));
        $adapter->setRequest($serviceLocator->get('request'));
        $adapter->setResponse($serviceLocator->get('response'));

        $return = new AuthenticationService(new NonPersistent, $adapter);

        return $return;
    }
}
