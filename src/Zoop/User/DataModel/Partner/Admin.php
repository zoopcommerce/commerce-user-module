<?php

namespace Zoop\User\DataModel\Partner;

use Zoop\Common\User\RoleAwareUserInterface;
use Zoop\Common\User\UserInterface as CommonUserInterface;
use Zoop\User\DataModel\UserInterface;
use Zoop\User\DataModel\AbstractUser;
use Zoop\User\Roles;
//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Zoop\Shard\Annotation\Annotations as Shard;

/**
 * @ODM\Document
 * @Shard\AccessControl({
 *     @Shard\Permission\Basic(roles="*", allow="read"),
 *     @Shard\Permission\Basic(roles="sys::auth-user", allow="*"),
 *     @Shard\Permission\Basic(roles="zoop-admin", allow="*"),
 *     @Shard\Permission\Basic(roles="partner-admin", allow="*"),
 *     @Shard\Permission\Basic(roles="company-admin", deny="*")
 * })
 */
class Admin extends AbstractUser implements 
    UserInterface,
    CommonUserInterface,
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
