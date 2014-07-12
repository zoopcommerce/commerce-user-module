<?php

namespace Zoop\User\DataModel\Company;

use Zoop\Common\User\RoleAwareUserInterface;
use Zoop\Common\User\UserInterface as CommonUserInterface;
use Zoop\Store\DataModel\StoresTrait;
use Zoop\User\DataModel\UserInterface;
use Zoop\User\DataModel\AbstractUser;
use Zoop\User\DataModel\Roles;
//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Zoop\Shard\Annotation\Annotations as Shard;

/**
 * @ODM\Document
 * 
 * @Shard\AccessControl({
 *     @Shard\Permission\Basic(roles="zoop-admin", allow="*"),
 *     @Shard\Permission\Basic(roles="partner-admin", allow="*"),
 *     @Shard\Permission\Basic(roles="company-admin", allow="*")
 * })
 */
class Admin extends AbstractUser implements 
    UserInterface,
    CommonUserInterface,
    RoleAwareUserInterface
{
    use StoresTrait;
    
    public function __construct()
    {
        $this->addRole(Roles::COMPANY_ADMIN);
    }
}
