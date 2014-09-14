<?php

namespace Zoop\User;

use Doctrine\ODM\MongoDB\DocumentManager;
use Zoop\User\DataModel\AbstractUser;
use Zoop\User\DataModel\Guest;
use Zoop\User\DataModel\System;
use Zoop\User\Roles;
use Zend\Authentication\Adapter\Http\ResolverInterface;
use Zend\Authentication\Result;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zoop\Shard\Manifest;

/**
 *
 * @author Josh Stuart <josh.stuart@zoopcommerce.com>
 */
class HttpBasicResolver implements ResolverInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    const USER_COLLECTION = 'Zoop\User\DataModel\AbstractUser';
    protected $sysUser;

    /**
     * @param type $username
     * @param type $realm
     * @param type $password
     * @return Result
     */
    public function resolve($username, $realm, $password = null)
    {
        if (!empty($username)) {
            $user = $this->getUserFromHttpAuth($username, $realm, $password);
        } else {
            $user = $this->getUserFromSession();
        }

        if (empty($user)) {
            $user = new Guest;
        }

        return new Result(Result::SUCCESS, $user);
    }

    /**
     *
     * @param string $username
     * @param string $realm
     * @param string|null $password
     * @return AbstractUser|null
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function getUserFromHttpAuth($username, $realm, $password = null)
    {
        $this->initTempUser();

        $dm = $this->getDocumentManager();
        $qb = $dm->createQueryBuilder(self::USER_COLLECTION);

        $qb->field('apiCredentials')->elemMatch(
            $qb->expr()->field('key')->equals($username)
                ->field('secret')->equals($password)
        );

        $user = $qb->getQuery()
            ->getSingleResult();

        $this->destroyTempUser();

        return $user;
    }

    /**
     * @return AbstractUser|null
     */
    protected function getUserFromSession()
    {
        $sessionContainer = $this->getServiceLocator()
            ->get('zoop.commerce.common.session.container.user');

        /* @var $sessionContainer Container */
        if (isset($sessionContainer->id)) {
            return $this->getDocumentManager()
                ->createQueryBuilder()
                ->find(self::USER_COLLECTION)
                ->field('id')->equals($sessionContainer->id)
                ->getQuery()
                ->getSingleResult();
        }

        return null;
    }

    /**
     * @return DocumentManager
     */
    protected function getDocumentManager()
    {
        return $this->getServiceLocator()
            ->get('shard.commerce.modelmanager');
    }

    /**
     * @return Manifest
     */
    protected function getManifest()
    {
        return $this->getServiceLocator()
            ->get('shard.commerce.manifest');
    }

    /**
     * @return System
     */
    public function getSysUser()
    {
        return $this->sysUser;
    }

    /**
     *
     * @param System $sysUser
     */
    public function setSysUser(System $sysUser)
    {
        $this->sysUser = $sysUser;
    }

    /**
     * Need to temporarily change user for AccessControl
     * to allow update even though there is no authenticated user
     */
    protected function initTempUser()
    {
        $sysUser = new System;
        $sysUser->addRole(Roles::SYSTEM_AUTH_USER);
        $this->getServiceLocator()->setService('user', $sysUser);

        $this->setSysUser($sysUser);
    }

    /**
     * Need to temporarily change user for AccessControl
     * to allow update even though there is no authenticated user
     */
    protected function destroyTempUser()
    {
        $sysUser = $this->getSysUser();
        $sysUser->removeRole(Roles::SYSTEM_AUTH_USER);
    }
}
