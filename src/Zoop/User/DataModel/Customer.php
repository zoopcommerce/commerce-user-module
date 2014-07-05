<?php

namespace Zoop\User\DataModel;

use Zoop\Common\User\RoleAwareUserInterface;
use Zoop\User\DataModel\UserInterface;
use Zoop\User\DataModel\AbstractUser;
use Zoop\User\DataModel\Roles;
//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Zoop\Shard\Annotation\Annotations as Shard;

/**
 * @ODM\Document
 * @Shard\AccessControl({
 *     @Shard\Permission\Basic(roles="*", allow="*")
 * })
 */
class Customer extends AbstractUser implements UserInterface, RoleAwareUserInterface
{
    public function __construct()
    {
        $this->addRole(Roles::CUSTOMER);
    }
}
