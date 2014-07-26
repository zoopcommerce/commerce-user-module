<?php

namespace Zoop\User\Test\Assets;

use Doctrine\ODM\MongoDB\DocumentManager;
use Zoop\User\DataModel\Zoop\Admin as ZoopAdmin;

class TestJsonData
{

    /**
     * @param DocumentManager $dm
     * @param string $dbName
     */
    public static function createZoopAdminUser(DocumentManager $dm, $dbName)
    {
        $json = self::getJson('DataModel/Zoop/Admin');
        self::insertDocument($dm, $dbName, 'User', $json);
    }

    /**
     * @param DocumentManager $dm
     * @param string $dbName
     */
    public static function createPartnerAdminUser(DocumentManager $dm, $dbName)
    {
        $json = self::getJson('DataModel/Partner/Admin');
        self::insertDocument($dm, $dbName, 'User', $json);
    }

    /**
     * @param DocumentManager $dm
     * @param string $dbName
     */
    public static function createCompanyAdminUser(DocumentManager $dm, $dbName)
    {
        $json = self::getJson('DataModel/Company/Admin');
        self::insertDocument($dm, $dbName, 'User', $json);
    }

    /**
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
     * @param string $collectionName
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
