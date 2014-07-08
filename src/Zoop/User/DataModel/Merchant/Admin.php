<?php

namespace Zoop\User\DataModel\Merchant;

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
    protected $brands = [];
    
    public function __construct()
    {
        $this->addRole(Roles::PARTNER_ADMIN);
    }

    /**
     * @return array
     */
    public function getBrands()
    {
        if (!is_array($this->brands)) {
            $this->brands = [];
        }
        return $this->brands;
    }

    /**
     * @param array $brands
     */
    public function setBrands(array $brands)
    {
        $this->brands = $brands;
    }

    /**
     * @param string $brand
     */
    public function addBrand($brand)
    {
        if (!empty($brand) && in_array($brand, $this->getBrands()) === false) {
            $this->brands[] = $brand;
        }
    }
}
