<?php

namespace Zoop\User\Test;

use Zoop\Store\DataModel\Store;
use Zoop\User\DataModel\AbstractUser;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Doctrine\ODM\MongoDB\DocumentManager;
use Zoop\Shard\Core\Events;
use Zoop\Shard\Manifest;
use Zoop\Shard\Serializer\Unserializer;
use Zoop\User\Test\Assets\TestData;

abstract class AbstractTest extends AbstractHttpControllerTestCase
{
    protected static $documentManager;
    protected static $dbName;
    protected static $manifest;
    protected static $unserializer;
    public $calls;

    public function setUp()
    {
        $this->setApplicationConfig(
            require __DIR__ . '/../../../test.application.config.php'
        );
        
        if(!isset(self::$documentManager)) {
            self::$documentManager = $this->getApplicationServiceLocator()
                ->get('doctrine.odm.documentmanager.commerce');
            
            self::$dbName = $this->getApplicationServiceLocator()
                ->get('config')['doctrine']['odm']['connection']['commerce']['dbname'];
            
            self::$manifest = $this->getApplicationServiceLocator()
                ->get('shard.commerce.manifest');

            self::$unserializer = self::$manifest->getServiceManager()
                ->get('unserializer');
            
            $eventManager = self::$documentManager->getEventManager();
            $eventManager->addEventListener(Events::EXCEPTION, $this);
        }
    }

    public static function tearDownAfterClass()
    {
        self::clearDb();
    }
    
    public static function clearDb()
    {
        $documentManager = self::getDocumentManager();
        
        if($documentManager instanceof DocumentManager) {
            $collections = $documentManager->getConnection()
                ->selectDatabase(self::getDbName())
                ->listCollections();

            foreach ($collections as $collection) {
                /* @var $collection \MongoCollection */
                $collection->drop();
            }
        }
    }

    /**
     * @return DocumentManager
     */
    public static function getDocumentManager()
    {
        return self::$documentManager;
    }

    /**
     * @return string
     */
    public static function getDbName()
    {
        return self::$dbName;
    }

    /**
     *
     * @return Manifest
     */
    public static function getManifest()
    {
        return self::$manifest;
    }

    /**
     *
     * @return Unserializer
     */
    public static function getUnserializer()
    {
        return self::$unserializer;
    }

    public function __call($name, $arguments)
    {
        var_dump($name, $arguments);
        $this->calls[$name] = $arguments;
    }
    
    /**
     * 
     * @param string $id
     * @return AbstractUser
     */
    public function getUser($id)
    {
        return self::getDocumentManager()
            ->createQueryBuilder()
            ->find('Zoop\User\DataModel\AbstractUser')
            ->field('username')->equals($id)
            ->getQuery()
            ->getSingleResult();
    }

    /**
     * 
     * @param array $data
     * @return Store
     */
    public function createStore()
    {
        TestData::createStore(self::getDocumentManager(), self::getDbName());
    }
}
