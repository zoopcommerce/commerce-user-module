<?php

namespace Zoop\User\DataModel;

use Doctrine\Common\Collections\ArrayCollection;
use Zoop\Store\DataModel\StoresTrait;
use Zoop\User\DataModel\ApiCredential;
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

    /**
     * @ODM\EmbedMany(targetDocument="\Zoop\User\DataModel\ApiCredential")
     */
    protected $apiCredentials = [];
    
    /**
     * 
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * 
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * 
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * 
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * 
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * 
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
    
    /**
     * @return ArrayCollection
     */
    public function getApiCredentials()
    {
        if(!$this->apiCredentials instanceof ArrayCollection) {
            $this->apiCredentials = new ArrayCollection;
        }
        return $this->apiCredentials;
    }

    /**
     * @param ArrayCollection $apiCredentials
     */
    public function setApiCredentials(ArrayCollection $apiCredentials)
    {
        $this->apiCredentials = $apiCredentials;
    }
    
    /**
     * @param ApiCredential $apiCredential
     */
    public function addApiCredential(ApiCredential $apiCredential)
    {
        if (!$this->getApiCredentials()->contains($apiCredential)) {
            $this->getApiCredentials()->add($apiCredential);
        }
    }
}
