<?php

/**
* @package Zoop
*/
namespace Zoop\User\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zoop\User\Crypt\Salt;

/**
 *
 * @since 1.0
 * @version $Revision$
 * @author Josh Stuart <josh.stuart@zoopcommerce.com>
 */
class PasswordSaltFactory implements FactoryInterface
{
    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return \Zoop\User\Crypt\Salt
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new Salt($serviceLocator->get('config')['zoop']['user']['crypt']['salt']['password']);
    }
}
