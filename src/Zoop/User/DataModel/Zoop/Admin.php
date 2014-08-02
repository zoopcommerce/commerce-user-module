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
 * @ODM\HasLifecycleCallbacks
 * @Shard\AccessControl({
 *     @Shard\Permission\Basic(roles={"sys::authenticate", "owner"}, allow="read"),
 *     @Shard\Permission\Basic(roles="owner", allow="update::*", deny="update::roles"),
 *     @Shard\Permission\Basic(roles="sys::recoverpassword", allow="update::password"),
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
    /**
     * @ODM\Collection
     */
    protected $roles = [
        Roles::ZOOP_ADMIN
    ];
}
