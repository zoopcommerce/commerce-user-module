<?php

namespace Zoop\User\Test\Services;

use Zend\Http\Header\Accept;
use Zend\Http\Header\ContentType;
use Zend\Http\Header\Origin;
use Zend\Http\Header\Host;
use Zend\Http\Header\GenericHeader;
use Zend\Http\Request;
use Zoop\User\Test\AbstractTest;
use Zoop\User\Test\Assets\TestJsonData;

class AuthenticationTest extends AbstractTest
{
    protected function tearDown()
    {
        self::clearDb();
    }
    
//    public function testControllerIdentity()
//    {
//        TestJsonData::createZoopAdminUser(self::getDocumentManager(), self::getDbName());
//        TestJsonData::createPartnerAdminUser(self::getDocumentManager(), self::getDbName());
//        TestJsonData::createCompanyAdminUser(self::getDocumentManager(), self::getDbName());
//        
//        $key = 'bigspaceship';
//        $secret = 'password1';
//        
//        $accept = new Accept;
//        $accept->addMediaType('application/json');
//        
//        $this->getRequest()
//            ->setMethod(Request::METHOD_GET)
//            ->getHeaders()->addHeaders([
//                $accept,
//                GenericHeader::fromString('Authorization: Basic ' . base64_encode(sprintf('%s:%s', $key, $secret)))
//            ]);
//
//        $this->dispatch('https://test.com/test');
//
//        $response = $this->getResponse();
//
//        $this->assertResponseStatusCode(200);
//        $this->assertEquals('true', $response->getContent());
//    }
    
    public function testAuthenticatedZoopAdminUser()
    {
        TestJsonData::createZoopAdminUser(self::getDocumentManager(), self::getDbName());
        TestJsonData::createPartnerAdminUser(self::getDocumentManager(), self::getDbName());
        TestJsonData::createCompanyAdminUser(self::getDocumentManager(), self::getDbName());
        
        $key = 'joshstuart';
        $secret = 'password1';
        
        $accept = new Accept;
        $accept->addMediaType('application/json');
        
        $request = $this->getRequest();
        
        $request->setMethod('GET')
            ->getHeaders()->addHeaders([
                $accept,
                Origin::fromString('Origin: http://api.zoopcommerce.local/ping'), 
                Host::fromString('Host: api.zoopcommerce.local'),
                ContentType::fromString('Content-type: application/json'),
                GenericHeader::fromString('Authorization: Basic ' . base64_encode(sprintf('%s:%s', $key, $secret)))
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
//        $this->assertEquals('josh@zoopcommerce.com', $user['email']);
        $this->assertEquals('Josh', $user['firstName']);
        $this->assertEquals('Stuart', $user['lastName']);
        $this->assertEquals('ZoopAdmin', $user['type']);
    }
}
