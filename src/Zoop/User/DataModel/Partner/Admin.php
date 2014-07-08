<?php

namespace Zoop\User\DataModel\Partner;

use Zoop\Common\User\RoleAwareUserInterface;
use Zoop\Common\User\UserInterface as CommonUserInterface;
use Zoop\User\DataModel\UserInterface;
use Zoop\User\DataModel\AbstractUser;
use Zoop\User\DataModel\Roles;

/**
 * @ODM\Document
 * @Shard\AccessControl({
 *     @Shard\Permission\Basic(roles="*", allow="*")
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
