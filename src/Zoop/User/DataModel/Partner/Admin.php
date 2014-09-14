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
 *     @Shard\Permission\Basic(roles="zoop::admin", allow="*"),
 *     @Shard\Permission\Basic(roles={"sys::authenticate", "sys::auth-user", "owner"}, allow="read"),
 *     @Shard\Permission\Basic(roles="sys::recoverpassword", allow="update::password"),
 *     @Shard\Permission\Basic(roles="partner::admin", allow="*", deny="update::roles"),
 *     @Shard\Permission\Basic(roles="owner", allow="update::*", deny="update::roles")
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
        Roles::PARTNER_ADMIN
    ];
    
    /**
     * @ODM\Collection
     * @ODM\Index
     */
    protected $companies = [];

    /**
     * @return array
     */
    public function getCompanies()
    {
        if (!is_array($this->companies)) {
            $this->companies = [];
        }
        return $this->companies;
    }

    /**
     * @param array $companies
     */
    public function setCompanies(array $companies)
    {
        $this->companies = $companies;
    }

    /**
     * @param string $company
     */
    public function addCompany($company)
    {
        if (!empty($company) && in_array($company, $this->getCompanies()) === false) {
            $this->companies[] = $company;
        }
    }
}
