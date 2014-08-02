<?php

namespace Zoop\User\Test\Assets;

use Zend\ServiceManager\ServiceManager;
use Doctrine\ODM\MongoDB\DocumentManager;
use Zoop\GomiModule\DataModel\User;
use Zoop\Shard\Serializer\Unserializer;
use Zoop\User\DataModel\Zoop\Admin as ZoopAdmin;
use Zoop\User\DataModel\Partner\Admin as PartnerAdmin;
use Zoop\User\DataModel\Company\Admin as CompanyAdmin;

class TestData
{
    const DOCUMENT_USER = 'Zoop\User\DataModel\Abstract';
    const ZOOP_ADMIN_USER = 'Zoop\User\DataModel\Zoop\Admin';
    const PARTNER_ADMIN_USER = 'Zoop\User\DataModel\Partner\Admin';
    const COMPANY_ADMIN_USER = 'Zoop\User\DataModel\Company\Admin';
    const STORE = 'Zoop\Store\DataModel\Store';

    /**
     * @param Unserializer $unserializer
     * @return CompanyAdmin
     */
    public static function getCompanyAdminUser(Unserializer $unserializer)
    {
        $data = self::getJson('DataModel/Company/Admin');
        return $unserializer->fromJson($data, self::DOCUMENT_USER);
    }

    /**
     * 
     * @param DocumentManager $dm
     * @param string $dbName
     */
    public static function createCompanyAdminUser(DocumentManager $dm, $dbName)
    {
        $json = self::getJson('DataModel/Company/Admin');
        self::insertDocument($dm, $dbName, 'User', $json);
    }

    /**
     * @param Unserializer $unserializer
     * @return PartnerAdmin
     */
    public static function getPartnerAdminUser(Unserializer $unserializer)
    {
        $data = self::getJson('DataModel/Partner/Admin');
        return $unserializer->fromJson($data, self::DOCUMENT_USER);
    }

    /**
     * 
     * @param DocumentManager $dm
     * @param string $dbName
     */
    public static function createPartnerAdminUser(DocumentManager $dm, $dbName)
    {
        $json = self::getJson('DataModel/Partner/Admin');
        self::insertDocument($dm, $dbName, 'User', $json);
    }

    /**
     * @param Unserializer $unserializer
     * @return ZoopAdmin
     */
    public static function getZoopAdminUser(Unserializer $unserializer)
    {
        $data = self::getJson('DataModel/Zoop/Admin');
        return $unserializer->fromJson($data, self::ZOOP_ADMIN_USER);
    }

    /**
     * @param ServiceManager $serviceLocator
     * @param DocumentManager $dm
     * @param Unserializer $unserializer
     */
    public static function createZoopAdminUser(ServiceManager $serviceLocator, DocumentManager $dm, Unserializer $unserializer)
    {
        $json = self::getJson('DataModel/Zoop/Admin');
        self::insertDocument($serviceLocator, $dm, $unserializer, self::ZOOP_ADMIN_USER, $json);
    }

    public static function createStore(ServiceManager $serviceLocator, DocumentManager $dm, Unserializer $unserializer)
    {
        $json = self::getJson('DataModel/Store');
        self::insertDocument($serviceLocator, $dm, $unserializer, self::STORE, $json);
    }

    /**
     * 
     * @param ServiceManager $serviceLocator
     * @param DocumentManager $documentManager
     */
    public static function createTestUser(ServiceManager $serviceLocator, DocumentManager $documentManager)
    {
        //craete temp auth user
        $sysUser = self::getSysUser($serviceLocator);

        $user = new User;
        $user->setUsername('joshstuart');
        $user->setFirstName('Josh');
        $user->setLastName('Stuart');
        $user->setEmail('josh@example.com');
        $user->setPassword('password1');
        $user->setSalt('passwordpasswordpasswordpasswordpassword');

        $documentManager->persist($user);
        $documentManager->flush($user);
        $documentManager->clear($user);

        $sysUser->removeRole('admin');
    }

    /**
     * 
     * @param ServiceManager $serviceLocator
     * @param DocumentManager $documentManager
     */
    public static function createZoopAdminUser2(ServiceManager $serviceLocator, DocumentManager $documentManager)
    {
        //craete temp auth user
        $sysUser = self::getSysUser($serviceLocator);

        $user = new ZoopAdmin;
        $user->setUsername('joshstuart');
        $user->setFirstName('Josh');
        $user->setLastName('Stuart');
        $user->setEmail('josh@example.com');
        $user->setPassword('password1');
        $user->setSalt('passwordpasswordpasswordpasswordpassword');

        $documentManager->persist($user);
        $documentManager->flush($user);
        $documentManager->clear($user);

        $sysUser->removeRole('admin');
    }

    /**
     * 
     * @param string $fileName
     * @return boolean
     */
    protected static function getJson($fileName)
    {
        return file_get_contents(__DIR__ . '/' . $fileName . '.json');
    }

    /**
     * @param ServiceManager $serviceLocator
     * @param DocumentManager $dm
     * @param Unserializer $unserializer
     * @param string $class
     * @param string $json
     */
    protected static function insertDocument(ServiceManager $serviceLocator, DocumentManager $dm, Unserializer $unserializer, $class, $json)
    {
        $sysUser = self::getSysUser($serviceLocator);

        $document = $unserializer->fromJson($json, $class);

        $dm->persist($document);
        $dm->flush($document);
        $dm->clear($document);
        
        $sysUser->removeRole('admin');

        return $document;
    }
    
    /**
     * @param ServiceManager $serviceLocator
     * @return User
     */
    protected static function getSysUser(ServiceManager $serviceLocator)
    {
        $sysUser = new User;
        $sysUser->addRole('admin');
        $serviceLocator->setService('user', $sysUser);
        return $sysUser;
    }
    
     protected static function insertJsonDocument(DocumentManager $dm, $dbName, $collectionName, $json)
    {
        $data = json_decode($json, true);
        $db = $dm->getConnection()->selectDatabase($dbName);
        
        $collection = $db->selectCollection($collectionName);
        return $collection->insert($data);
    }
}
