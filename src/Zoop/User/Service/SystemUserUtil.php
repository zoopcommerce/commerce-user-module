<?php

/**
 * @package    Zoop
 */

namespace Zoop\User\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zoop\Common\User\UserInterface;
use Zoop\GomiModule\DataModel\User;

/**
 * @since   1.0
 * @version $Revision$
 * @author  Josh Stuart <josh.stuart@zoopcommerce.com>
 */
class SystemUserUtil implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    const SYSTEM_USER = 'sys::user';

    protected $allowOverride;
    protected $systemUser;
    protected $activeUser;
    protected $systemRole;

    /**
     * Inserts a system user into the service manager, storing
     * the current user if it exists.
     *
     * @param string $role
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function addSystemUser($role = null)
    {
        $serviceManager = $this->getServiceLocator();

        $allowOverride = $serviceManager->getAllowOverride();
        $this->setAllowOverride($allowOverride);

        //try to get an active user and store it for later usage
        try {
            $activeUser = $serviceManager->get('user');
            if (!empty($activeUser)) {
                $this->setActiveUser($activeUser);
            }
        } catch (Exception $error) {

        }

        $serviceManager->setAllowOverride(true);
        $sysUser = new User;
        if (empty($role)) {
            $role = self::SYSTEM_USER;
        }

        $this->setSystemRole($role);
        $sysUser->addRole($role);
        $serviceManager->setService('user', $sysUser);
        $this->setSystemUser($sysUser);
    }

    /**
     * Removes the system user from the service manager and reinstates the
     * active user if one exists.
     */
    public function removeSystemUser()
    {
        $serviceManager = $this->getServiceLocator();

        $systemUser = $this->getSystemUser();
        if (!empty($systemUser)) {
            $systemUser->removeRole($this->getSystemRole());
        }

        $activeUser = $this->getActiveUser();
        if (!empty($activeUser)) {
            $serviceManager->setAllowOverride(true);
            $serviceManager->setService('user', $activeUser);
        }
        $serviceManager->setAllowOverride($this->getAllowOverride());
    }

    /**
     * @return UserInterface
     */
    public function getSystemUser()
    {
        return $this->systemUser;
    }

    /**
     * @param UserInterface $systemUser
     */
    public function setSystemUser(UserInterface $systemUser)
    {
        $this->systemUser = $systemUser;
    }

    /**
     * @return UserInterface
     */
    public function getActiveUser()
    {
        return $this->activeUser;
    }

    /**
     * @param UserInterface $activeUser
     */
    public function setActiveUser(UserInterface $activeUser)
    {
        $this->activeUser = $activeUser;
    }

    /**
     * @return string
     */
    public function getSystemRole()
    {
        return $this->systemRole;
    }

    /**
     * @param string $systemRole
     */
    public function setSystemRole($systemRole)
    {
        $this->systemRole = $systemRole;
    }

    /**
     * @return boolean
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getAllowOverride()
    {
        return $this->allowOverride;
    }

    /**
     * @param boolean $allowOverride
     */
    public function setAllowOverride($allowOverride)
    {
        $this->allowOverride = (boolean) $allowOverride;
    }
}
