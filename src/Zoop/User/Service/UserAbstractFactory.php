<?php
/**
 * @package    Zoop
 * @license    MIT
 */
namespace Zoop\User\Service;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zoop\Common\User\UserInterface;
use Zoop\User\Events;
use Zoop\ShardModule\Exception\AccessControlException;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Josh Stuart <josh.stuart@zoopcommerce.com>
 */
class UserAbstractFactory implements AbstractFactoryInterface
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        if ($name == 'user' && $serviceLocator->has('Zend\Authentication\AuthenticationService')) {
            $authenticationService = $serviceLocator->get('Zend\Authentication\AuthenticationService');
            if ($authenticationService->hasIdentity()) {
                $this->triggerUserEvent($serviceLocator, $authenticationService->getIdentity());
                return true;
            } else {
                throw new AccessControlException("Cannot authenticate user");
            }
        }
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return $serviceLocator->get('Zend\Authentication\AuthenticationService')->getIdentity();
    }

    /**
     * Triggers an event with the authenticated user on the application event manager
     *
     * @param UserInterface $user
     */
    protected function triggerUserEvent(ServiceLocatorInterface $serviceLocator, UserInterface $user)
    {
        $serviceLocator->get('Application')
            ->getEventManager()
            ->trigger(Events::USER_POST_AUTH, null, $user);
    }
}
