<?php

namespace Zoop\User\Service;

use Zoop\User\DataModel\UserInterface;
use Zoop\User\DataModel\Guest;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;

/**
 * 
 * @author Josh Stuart <josh.stuart@zoopcommerce.com>
 */
class ActiveUserFactory implements FactoryInterface
{
    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return UserInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $sessionContainer = $serviceLocator->get('zoop.commerce.common.session.container.user');

        /* @var $sessionContainer Container */
        if (isset($sessionContainer->id)) {
            $user = $this->loadUser($sessionContainer->id, $serviceLocator);
        }

        if (empty($user)) {
            $user = new Guest;
        }

        return $user;
    }

    /**
     * @param string $id
     * @param ServiceLocatorInterface $serviceLocator
     * @return UserInterface|null
     */
    protected function loadUser($id, ServiceLocatorInterface $serviceLocator)
    {
        $documentManager = $serviceLocator->get('shard.commerce.modelmanager');

        $user = $documentManager
            ->createQueryBuilder()
            ->find('Zoop\User\DataModel\AbstractUser')
            ->field('id')->equals($id)
            ->getQuery()
            ->getSingleResult();

        return $user;
    }
}
