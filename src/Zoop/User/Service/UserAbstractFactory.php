<?php
/**
 * @package    Zoop
 * @license    MIT
 */
namespace Zoop\User\Service;

use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zoop\Common\User\UserInterface;

/**
 *
 * @since   1.0
 * @version $Revision$
 * @author  Josh Stuart <josh.stuart@zoopcommerce.com>
 */
class UserAbstractFactory implements AbstractFactoryInterface, EventManagerAwareInterface
{
    use EventManagerAwareTrait;
    
    const EVENT_USER = 'user';
    
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        if ($name == 'user' && $serviceLocator->has('Zend\Authentication\AuthenticationService')) {
            $authenticationService = $serviceLocator->get('Zend\Authentication\AuthenticationService');
            if ($authenticationService->hasIdentity() || $authenticationService->authenticate()->isValid()) {
                $this->triggerUserEvent($authenticationService->getIdentity());
                
                return true;
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
     * Triggers an event with the authenticated user
     * 
     * @param UserInterface $user
     */
    protected function triggerUserEvent(UserInterface $user)
    {
        $this->getEventManager()
            ->trigger(self::EVENT_USER, null, ['user' => $user]);
    }
}
