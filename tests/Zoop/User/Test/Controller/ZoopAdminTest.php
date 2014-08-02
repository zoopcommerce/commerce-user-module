<?php

namespace Zoop\User\Test\Controller;

use Zend\Http\Header\Accept;
use Zend\Http\Header\ContentType;
use Zend\Http\Header\Origin;
use Zend\Http\Header\Host;
use Zend\Http\Header\GenericHeader;
use Zend\Http\Request;
use Zoop\User\Test\AbstractTest;
use Zoop\User\Test\Assets\TestJsonData;

class ZoopAdminTest extends AbstractTest
{
    private static $zoopAdminKey = 'joshstuart';
    private static $zoopAdminSecret = 'password1';
    
    protected function tearDown()
    {
        self::clearDb();
    }
    
    public function setUp()
    {
        parent::setUp();
        TestJsonData::createZoopAdminUser(self::getDocumentManager(), self::getDbName());
        TestJsonData::createPartnerAdminUser(self::getDocumentManager(), self::getDbName());
        TestJsonData::createCompanyAdminUser(self::getDocumentManager(), self::getDbName());
    }
    
    public function testGetUsers()
    {
        $accept = new Accept;
        $accept->addMediaType('application/json');
        
        $request = $this->getRequest();
        
        $request->setMethod('GET')
            ->getHeaders()->addHeaders([
                $accept,
                Origin::fromString('Origin: http://api.zoopcommerce.local/ping'), 
                Host::fromString('Host: api.zoopcommerce.local'),
                ContentType::fromString('Content-type: application/json'),
                GenericHeader::fromString('Authorization: Basic ' . base64_encode(sprintf('%s:%s', self::$zoopAdminKey, self::$zoopAdminSecret)))
            ]);
        
        $this->dispatch('http://api.zoopcommerce.local/users');
        $response = $this->getResponse();
        
        $this->assertResponseStatusCode(200);
        
        $json = $response->getContent();
        $this->assertJson($json);
        
        $content = json_decode($json, true);
        
        $this->assertCount(3, $content);
        
        $user = $content[0];
        
        $this->assertEquals('joshstuart', $user['username']);
        $this->assertEquals('Josh', $user['firstName']);
        $this->assertEquals('Stuart', $user['lastName']);
        $this->assertEquals('ZoopAdmin', $user['type']);
    }
    
    public function testCreateUser()
    {
        $accept = new Accept;
        $accept->addMediaType('application/json');
        
        $request = $this->getRequest();
        
        $userData = TestJsonData::getCreateZoopAdminUser();
        
        $request->setMethod('POST')
            ->setContent($userData)
            ->getHeaders()->addHeaders([
                $accept,
                Origin::fromString('Origin: http://api.zoopcommerce.local/ping'), 
                Host::fromString('Host: api.zoopcommerce.local'),
                ContentType::fromString('Content-type: application/json'),
                GenericHeader::fromString('Authorization: Basic ' . base64_encode(sprintf('%s:%s', self::$zoopAdminKey, self::$zoopAdminSecret)))
            ]);
        
        $this->dispatch('http://api.zoopcommerce.local/users');
        $response = $this->getResponse();
        
        $this->assertResponseStatusCode(201);
    }
}
