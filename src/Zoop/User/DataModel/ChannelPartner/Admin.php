<?php

namespace Zoop\User\DataModel\ChannelPartner;

use Zoop\Common\User\RoleAwareUserInterface;
use  Zoop\User\DataModel\UserInterface;
use Zoop\User\DataModel\AbstractUser;
use Zoop\User\DataModel\Roles;

/**
 * @ODM\Document
 * @Shard\AccessControl({
 *     @Shard\Permission\Basic(roles="*", allow="*")
 * })
 */
class Admin extends AbstractUser implements UserInterface, RoleAwareUserInterface
{
    public function __construct()
    {
        $this->addRole(Roles::CHANNEL_PARTNER_ADMIN);
    }
}
