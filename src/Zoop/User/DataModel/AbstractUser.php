<?php

namespace Zoop\User\DataModel;

use Doctrine\Common\Collections\ArrayCollection;
use Zoop\User\DataModel\ApiCredential;
use Zoop\Shard\Stamp\DataModel\CreatedOnTrait;
use Zoop\Shard\Stamp\DataModel\UpdatedOnTrait;
use Zoop\Shard\SoftDelete\DataModel\SoftDeleteableTrait;
use Zoop\Shard\User\DataModel\PasswordTrait;
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
 * @ODM\HasLifecycleCallbacks
 * @ODM\InheritanceType("SINGLE_COLLECTION")
 * @ODM\DiscriminatorField(fieldName="type")
 * @ODM\DiscriminatorMap({
 *     "company::admin" = "Zoop\User\DataModel\Company\Admin",
 *     "customer" = "Zoop\User\DataModel\Customer",
 *     "guest" = "Zoop\User\DataModel\Guest",
 *     "partner::admin" = "Zoop\User\DataModel\Partner\Admin",
 *     "zoop::admin" = "Zoop\User\DataModel\Zoop\Admin"
 * })
 * @Shard\AccessControl({
 *     @Shard\Permission\Basic(roles="zoop::admin", allow="*"),
 *     @Shard\Permission\Basic(roles={"sys::authenticate", "sys::auth-user", "owner", "partner::admin", "company::admin", "store::admin"}, allow="read"),
 *     @Shard\Permission\Basic(roles="sys::recoverpassword", allow="update::password"),
 *     @Shard\Permission\Basic(roles="owner", allow="update::*", deny="update::roles")
 * })
 */
class AbstractUser
{
    use CreatedOnTrait;
    use UpdatedOnTrait;
    use SoftDeleteableTrait;
    use UserTrait;
    use PasswordTrait;
    use RoleAwareUserTrait;

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
     * @Shard\Serializer\Ignore("ignore_when_serializing")
     * @Shard\Crypt\BlockCipher(
     *     key = "crypt.emailaddress",
     *     salt = "crypt.emailaddress"
     * )
     * @Shard\Validator\Chain({
     *     @Shard\Validator\Required,
     *     @Shard\Validator\Email
     * })
     */
    protected $email;

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
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = (string) $email;
    }
    
    /**
     * @return ArrayCollection
     */
    public function getApiCredentials()
    {
        if (!$this->apiCredentials instanceof ArrayCollection) {
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
