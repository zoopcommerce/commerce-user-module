<?php

/**
 * @package    Zoop
 * @license    MIT
 */
namespace Zoop\User\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zoop\Sage\HttpBasicResolver;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Tim Roediger <superdweebie@gmail.com>
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
        $config = $serviceLocator->get('config')['zoop']['sage']['store_admin'];

        $return = new HttpBasicResolver();
        $return->setUsername($config['username']);
        $return->setPassword($config['password']);

        return $return;
    }
}
