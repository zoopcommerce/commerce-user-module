<?php

/**
 * @package    Zoop
 */

namespace Zoop\User\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author  Josh Stuart <josh.stuart@zoopcommerce.com>
 */
class UserAuthDocumentManagerFactory implements FactoryInterface
{
    /**
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return object
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $name = $serviceLocator->get('config')['zoop']['shard']['manifest']['userauth']['model_manager'];

        return $serviceLocator->get($name);
    }
}
