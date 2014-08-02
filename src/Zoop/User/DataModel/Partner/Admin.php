<?php

namespace Zoop\User\DataModel\Partner;

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
 *     @Shard\Permission\Basic(roles={"sys::authenticate", "owner"}, allow="read"),
 *     @Shard\Permission\Basic(roles="owner", allow="update::*", deny="update::roles"),
 *     @Shard\Permission\Basic(roles="sys::recoverpassword", allow="update::password"),
 *     @Shard\Permission\Basic(roles="zoop-admin", allow="*"),
 *     @Shard\Permission\Basic(roles="partner-admin", allow="create"),
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
     * @ODM\Index
     */
    protected $merchants = [];
    
    public function __construct()
    {
        $this->addRole(Roles::PARTNER_ADMIN);
    }

    /**
     * @return array
     */
    public function getMerchants()
    {
        if (!is_array($this->merchants)) {
            $this->merchants = [];
        }
        return $this->merchants;
    }

    /**
     * @param array $merchants
     */
    public function setMerchants(array $merchants)
    {
        $this->merchants = $merchants;
    }

    /**
     * @param string $merchant
     */
    public function addMerchant($merchant)
    {
        if (!empty($merchant) && in_array($merchant, $this->getMerchants()) === false) {
            $this->merchants[] = $merchant;
        }
    }
}
