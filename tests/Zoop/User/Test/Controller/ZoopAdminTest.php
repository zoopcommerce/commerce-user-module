<?php

namespace Zoop\User\Test\Controller;

use Zend\Http\Header\Accept;
use Zend\Http\Header\ContentType;
use Zend\Http\Header\Origin;
use Zend\Http\Header\Host;
use Zend\Http\Header\GenericHeader;
use Zoop\User\Test\AbstractTest;
use Zoop\User\Test\Assets\TestJsonData;

class ZoopAdminTest extends AbstractTest
{
    private static $zoopAdminKey = 'joshstuart';
    private static $zoopAdminSecret = 'password1';
    private static $zoopCreatedAdminUsername = 'timroediger';
    
    public function testGetUsers()
    {
        TestJsonData::createZoopAdminUser(self::getDocumentManager(), self::getDbName());
        TestJsonData::createPartnerAdminUser(self::getDocumentManager(), self::getDbName());
        TestJsonData::createCompanyAdminUser(self::getDocumentManager(), self::getDbName());
        
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
    
    public function testUpdateUser()
    {
        $accept = new Accept;
        $accept->addMediaType('application/json');
        
        $request = $this->getRequest();
        
        $userData = json_decode(TestJsonData::getCreateZoopAdminUser(), true);
        unset($userData['password']);
        unset($userData['username']);
        unset($userData['email']);
        unset($userData['type']);
        
        $userData['firstName'] = 'Tim 2';
        $userData['lastName'] = 'Roediger 2';
        
        $request->setMethod('PATCH')
            ->setContent(json_encode($userData))
            ->getHeaders()->addHeaders([
                $accept,
                Origin::fromString('Origin: http://api.zoopcommerce.local/ping'), 
                Host::fromString('Host: api.zoopcommerce.local'),
                ContentType::fromString('Content-type: application/json'),
                GenericHeader::fromString('Authorization: Basic ' . base64_encode(sprintf('%s:%s', self::$zoopAdminKey, self::$zoopAdminSecret)))
            ]);
        
        $this->dispatch(sprintf('http://api.zoopcommerce.local/users/%s', self::$zoopCreatedAdminUsername));
        $response = $this->getResponse();
        
        $this->assertResponseStatusCode(204);
    }
    
    public function testDeleteUser()
    {
        $accept = new Accept;
        $accept->addMediaType('application/json');
        
        $request = $this->getRequest();
        
        $request->setMethod('DELETE')
            ->getHeaders()->addHeaders([
                $accept,
                Origin::fromString('Origin: http://api.zoopcommerce.local/ping'), 
                Host::fromString('Host: api.zoopcommerce.local'),
                ContentType::fromString('Content-type: application/json'),
                GenericHeader::fromString('Authorization: Basic ' . base64_encode(sprintf('%s:%s', self::$zoopAdminKey, self::$zoopAdminSecret)))
            ]);
        
        $this->dispatch(sprintf('http://api.zoopcommerce.local/users/%s', self::$zoopCreatedAdminUsername));
        $response = $this->getResponse();
        
        $this->assertResponseStatusCode(204);
    }
}
