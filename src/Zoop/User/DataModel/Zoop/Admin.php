<?php

namespace Zoop\User\DataModel\Zoop;

use Zoop\Common\User\PasswordInterface;
use Zoop\Common\User\RoleAwareUserInterface;
use Zoop\Common\User\UserInterface;
use Zoop\User\DataModel\AbstractUser;
use Zoop\User\Roles;
//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Zoop\Shard\Annotation\Annotations as Shard;

/**
 * @ODM\Document
 * @Shard\AccessControl({
 *     @Shard\Permission\Basic(roles="zoop-admin", allow="*"),
 *     @Shard\Permission\Basic(roles="partner-admin", deny="*"),
 *     @Shard\Permission\Basic(roles="company-admin", deny="*")
 * })
 */
class Admin extends AbstractUser implements
    PasswordInterface,
    UserInterface,
    RoleAwareUserInterface
{
    public function __construct()
    {
        $this->addRole(Roles::ZOOP_ADMIN);
    }
}
