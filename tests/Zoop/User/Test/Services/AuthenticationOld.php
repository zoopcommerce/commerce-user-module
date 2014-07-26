<?php

namespace Zoop\User\Test\Services;

use Zend\Http\Header\Accept;
use Zend\Http\Header\ContentType;
use Zend\Http\Header\Origin;
use Zend\Http\Header\Host;
use Zend\Http\Header\GenericHeader;
use Zoop\User\Test\AbstractTest;
use Zoop\User\Test\Assets\TestData;
use Zoop\User\DataModel\Zoop\Admin as ZoopAdmin;

class AuthenticationOld extends AbstractTest
{
    protected function tearDown()
    {
        self::clearDb();
    }
    
    public function testAuthenticatedZoopAdminUser()
    {
        TestData::createZoopAdminUser(self::getDocumentManager(), self::getDbName());
        TestData::createPartnerAdminUser(self::getDocumentManager(), self::getDbName());
        TestData::createCompanyAdminUser(self::getDocumentManager(), self::getDbName());
        
        $key = 'zoop';
        $secret = 'uvBAJc3X';
        
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
        
        $this->dispatch('http://test.local/test');
        $response = $this->getResponse();
        
        $this->assertResponseStatusCode(200);
        
        $json = $response->getContent();
        $this->assertJson($json);
        
        $content = json_decode($json, true);
        
        $this->assertCount(3, $content);
        
        $user = $content[0];
        
        $this->assertEquals('joshstuart', $user['username']);
        $this->assertEquals('josh@zoopcommerce.com', $user['email']);
        $this->assertEquals('Josh', $user['firstName']);
        $this->assertEquals('Stuart', $user['lastName']);
        $this->assertEquals('ZoopAdmin', $user['type']);
    }
    
    public function testAuthenticatedPartnerAdminUser()
    {
        TestData::createZoopAdminUser(self::getDocumentManager(), self::getDbName());
        TestData::createPartnerAdminUser(self::getDocumentManager(), self::getDbName());
        TestData::createCompanyAdminUser(self::getDocumentManager(), self::getDbName());
        
        $key = 'partner';
        $secret = 'HVa2YyTJ';
        
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
        
        $this->assertCount(2, $content);
        
        $user = $content[0];
    }
}
