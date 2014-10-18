<?php

namespace Zoop\User\DataModel\Partner;

use Zoop\Common\User\PasswordInterface;
use Zoop\Common\User\RoleAwareUserInterface;
use Zoop\Common\User\UserInterface;
use Zoop\Company\DataModel\CompaniesTrait;
use Zoop\Company\DataModel\FilterCompaniesInterface;
use Zoop\Store\DataModel\FilterStoreInterface;
use Zoop\Store\DataModel\StoresTrait;
use Zoop\User\DataModel\AbstractUser;
use Zoop\User\DataModel\PartnerAdminInterface;
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
    FilterCompaniesInterface,
    FilterStoreInterface,
    PartnerAdminInterface,
    PasswordInterface,
    RoleAwareUserInterface,
    UserInterface
{
    use CompaniesTrait;
    use StoresTrait;

    /**
     * @ODM\Collection
     */
    protected $roles = [
        Roles::PARTNER_ADMIN
    ];

    /**
     * @ODM\String
     * @ODM\Index
     * @Shard\Zones
     */
    protected $partner;

    /**
     * @return string
     */
    public function getPartner()
    {
        return $this->partner;
    }

    /**
     * @param string $partner
     */
    public function setPartner($partner)
    {
        $this->partner = $partner;
    }
}
