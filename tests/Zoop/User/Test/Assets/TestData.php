<?php

namespace Zoop\User\Test\Assets;

use Doctrine\ODM\MongoDB\DocumentManager;
use Zoop\Shard\Serializer\Unserializer;
use Zoop\User\DataModel\Zoop\Admin as ZoopAdmin;
use Zoop\User\DataModel\Merchant\Admin as MerchantAdmin;
use Zoop\User\DataModel\Partner\Admin as PartnerAdmin;
use Zoop\User\DataModel\Brand\Admin as BrandAdmin;

class TestData
{
    const DOCUMENT_USER = 'Zoop\User\DataModel\Abstract';
    
    /**
     * @param Unserializer $unserializer
     * @return BrandAdmin
     */
    public static function getBrandAdminUser(Unserializer $unserializer)
    {
        $data = self::getJson('DataModel/Brand/Admin');
        return $unserializer->fromJson($data, self::DOCUMENT_USER);
    }
    
    /**
     * @param Unserializer $unserializer
     * @return MerchantAdmin
     */
    public static function getMerchantAdminUser(Unserializer $unserializer)
    {
        $data = self::getJson('DataModel/Merchant/Admin');
        return $unserializer->fromJson($data, self::DOCUMENT_USER);
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
