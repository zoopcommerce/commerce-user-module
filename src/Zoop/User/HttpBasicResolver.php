<?php

namespace Zoop\User;

use Zoop\User\DataModel\ApiCredential;
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
    
    /**
     * @param type $username
     * @param type $realm
     * @param type $password
     * @return Result
     */
    public function resolve($username, $realm, $password = null)
    {
        $credential = new ApiCredential($username, $password);
        $documentManager = $this->getServiceLocator()
            ->get('shard.commerce.modelmanager');

        $user = $documentManager
            ->createQueryBuilder()
            ->find('Zoop\User\DataModel\AbstractUser')
            ->field('apiCredentials')->elemMatch($credential)
            ->getQuery()
            ->getSingleResult();
        
        if (empty($user)) {
            $user = new Guest;
        }
        return new Result(Result::SUCCESS, $user);
    }
}
