<?php

namespace Zoop\User;

use Doctrine\ODM\MongoDB\DocumentManager;
use Zoop\User\DataModel\AbstractUser;
use Zoop\User\DataModel\Guest;
use Zend\Authentication\Adapter\Http\ResolverInterface;
use Zend\Authentication\Result;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * 
 * @author Josh Stuart <josh.stuart@zoopcommerce.com>
 */
class HttpBasicResolver implements ResolverInterface, ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    const USER_COLLECTION = 'Zoop\User\DataModel\AbstractUser';

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
     */
    protected function getUserFromHttpAuth($username, $realm, $password = null)
    {
        $qb = $this->getDocumentManager()
            ->createQueryBuilder(self::USER_COLLECTION);
        
        $qb->field('apiCredentials')->elemMatch(
            $qb->expr()->field('key')->equals($username)
                ->field('secret')->equals($password)
        );
        
        return $qb->getQuery()
            ->getSingleResult();
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
}
