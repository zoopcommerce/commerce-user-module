<?php

namespace Zoop\User\DataModel\Company;

use Zoop\Common\User\PasswordInterface;
use Zoop\Common\User\RoleAwareUserInterface;
use Zoop\Common\User\UserInterface;
use Zoop\Store\DataModel\FilterStoreInterface;
use Zoop\Store\DataModel\StoresTrait;
use Zoop\User\DataModel\AbstractUser;
use Zoop\User\DataModel\CompanyAdminInterface;
use Zoop\User\DataModel\UserInterface as UserModelInterface;
use Zoop\User\Roles;
//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Zoop\Shard\Annotation\Annotations as Shard;

/**
 * @ODM\Document
 * @Shard\AccessControl({
 *     @Shard\Permission\Basic(roles="zoop::admin", allow="*"),
 *     @Shard\Permission\Basic(roles={"sys::authenticate", "sys::auth-user", "owner"}, allow="read"),
 *     @Shard\Permission\Basic(roles="sys::recoverpassword", allow="update::password"),
 *     @Shard\Permission\Basic(roles="partner::admin", allow="*", deny="update::roles"),
 *     @Shard\Permission\Basic(roles="company::admin", allow={"create", "update::*"}, deny="update::roles"),
 *     @Shard\Permission\Basic(roles="owner", allow="update::*", deny="update::roles")
 * })
 */
class Admin extends AbstractUser implements
    CompanyAdminInterface,
    FilterStoreInterface,
    PasswordInterface,
    RoleAwareUserInterface,
    UserInterface,
    UserModelInterface
{
    use StoresTrait;

    /**
     * @ODM\Collection
     */
    protected $roles = [
        Roles::COMPANY_ADMIN
    ];

    /**
     * @ODM\String
     * @ODM\Index
     * @Shard\Zones
     */
    protected $company;

    /**
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param string $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }
}
