<?php

namespace Zoop\User\DataModel;

use Zoop\Common\User\RoleAwareUserInterface;
use Zoop\Common\User\UserInterface as GatewayUserInterface;
use Zoop\User\DataModel\AbstractUser;
use Zoop\User\DataModel\UserInterface as UserModelInterface;
use Zoop\User\Roles;
//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Zoop\Shard\Annotation\Annotations as Shard;

/**
 * @ODM\Document
 * @Shard\AccessControl({
 *      @Shard\Permission\Basic(roles={"sys::auth-user", "zoop::admin"}, allow="*"),
 *      @Shard\Permission\Basic(
 *          roles={
 *              "partner::admin",
 *              "company::admin",
 *              "store::admin"
 *          },
 *          allow={
 *              "create",
 *              "read",
 *              "update"
 *          }
 *      )
 * })
 */
class Customer extends AbstractUser implements
    RoleAwareUserInterface,
    GatewayUserInterface,
    UserModelInterface
{
    public function __construct()
    {
        $this->addRole(Roles::CUSTOMER);
    }
}
