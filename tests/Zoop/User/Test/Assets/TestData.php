<?php

namespace Zoop\User\Test\Assets;

use Doctrine\ODM\MongoDB\DocumentManager;
use Zoop\GomiModule\DataModel\User;
use Zoop\Shard\Serializer\Unserializer;
use Zoop\User\DataModel\Zoop\Admin as ZoopAdmin;
use Zoop\User\DataModel\Partner\Admin as PartnerAdmin;
use Zoop\User\DataModel\Company\Admin as CompanyAdmin;

class TestData
{
    const DOCUMENT_USER = 'Zoop\User\DataModel\Abstract';
    
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
        return $unserializer->fromJson($data, self::DOCUMENT_USER);
    }
    
    /**
     * 
     * @param DocumentManager $dm
     * @param string $dbName
     */
    public static function createZoopAdminUser(DocumentManager $dm, $dbName)
    {
        $json = self::getJson('DataModel/Zoop/Admin');
        self::insertDocument($dm, $dbName, 'User', $json);
    }
    
    /**
     * 
     * @param DocumentManager $dm
     * @param string $dbName
     */
    public static function createStore(DocumentManager $dm, $dbName)
    {
        $json = self::getJson('DataModel/Store');
        self::insertDocument($dm, $dbName, 'Store', $json);
    }
    
    public static function createTestUser($serviceLocator, DocumentManager $documentManager)
    {
        //craete temp auth user
        $sysUser = new User;
        $sysUser->addRole('admin');
        $serviceLocator->setService('user', $sysUser);
        
        $user = new User;
        $user->setUsername('joshstuart');
        $user->setFirstName('Josh');
        $user->setLastName('Stuart');
        $user->setEmail('josh@example.com');
        $user->setPassword('password1');
        $user->setSalt('passwordpasswordpasswordpasswordpassword');

        $documentManager->persist($user);

        $documentManager->flush();
        
        $sysUser->removeRole('admin');
        $documentManager->clear();
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
     * @param DocumentManager $dm
     * @param string $dbName
     * @param string $json
     * @return boolean
     */
    protected static function insertDocument(DocumentManager $dm, $dbName, $collectionName, $json)
    {
        $data = json_decode($json, true);
        $db = $dm->getConnection()->selectDatabase($dbName);
        
        $collection = $db->selectCollection($collectionName);
        return $collection->insert($data);
    }
}
