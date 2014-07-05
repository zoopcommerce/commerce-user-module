<?php

namespace Zoop\User\DataModel;

use Zoop\Store\DataModel\StoresTrait;
use Zoop\Shard\Stamp\DataModel\CreatedOnTrait;
use Zoop\Shard\Stamp\DataModel\UpdatedOnTrait;
use Zoop\Shard\SoftDelete\DataModel\SoftDeleteableTrait;
use Zoop\Shard\User\DataModel\UserTrait;
use Zoop\Shard\User\DataModel\RoleAwareUserTrait;
//Annotation imports
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Zoop\Shard\Annotation\Annotations as Shard;

/**
 * @author Josh Stuart <josh.stuart@zoopcommerce.com>
 *
 * @ODM\Document(
 *     collection="User",
 *     indexes = {
 *         @ODM\Index(
 *              keys={
 *                  "email"="asc",
 *                  "username"="asc"
 *              }
 *         )
 *     }
 * )
 * @ODM\InheritanceType("SINGLE_COLLECTION")
 * @ODM\DiscriminatorField(fieldName="type")
 * @ODM\DiscriminatorMap({
 *     "ZoopSuperAdmin"                 = "Zoop\User\DataModel\Zoop\SuperAdmin",
 *     "ChannelPartnerSuperAdmin"       = "Zoop\User\DataModel\ChannelPartner\SuperAdmin",
 *     "ChannelPartnerAdmin"            = "Zoop\User\DataModel\ChannelPartner\Admin",
 *     "StoreSuperAdmin"                = "Zoop\User\DataModel\Store\SuperAdmin",
 *     "StoreAdmin"                     = "Zoop\User\DataModel\Store\Admin",
 *     "Customer"                       = "Zoop\User\DataModel\Customer"
 * })
 * @Shard\AccessControl({
 *     @Shard\Permission\Basic(roles="*", allow="*")
 * })
 */
class AbstractUser
{
    use CreatedOnTrait;
    use UpdatedOnTrait;
    use SoftDeleteableTrait;
    use UserTrait;
    use RoleAwareUserTrait;
    use StoresTrait;

    /**
     * @ODM\Id(strategy="UUID")
     */
    protected $id;

    /**
     * @ODM\String
     */
    protected $firstName;

    /**
     * @ODM\String
     */
    protected $lastName;

    /**
     * @ODM\String
     */
    protected $email;

    /**
     * @ODM\String
     * @Shard\Crypt\Hash(
     *     salt = "zoop.user.password.salt"
     * )
     */
    protected $password;

    public function getId()
    {
        return $this->id;
    }
    
    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }
}
