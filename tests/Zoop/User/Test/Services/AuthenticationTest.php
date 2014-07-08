<?php

namespace Zoop\User\Test\Services;

use Zend\Http\Header\Accept;
use Zend\Http\Header\ContentType;
use Zend\Http\Header\Origin;
use Zend\Http\Header\Host;
use Zend\Http\Header\GenericHeader;
use Zoop\User\DataModel\ApiCredential;
use Zoop\User\Test\AbstractTest;
use Zoop\User\Test\Assets\TestData;

class AuthenticationTest extends AbstractTest
{
    public function testAuthenticatedZoopSuperUser()
    {
        $key = 'zoop';
        $secret = 'commerce';
        
        TestData::createZoopAdminUser(self::getDocumentManager(), self::getDbName());
        
        $this->createStore();
        
        $accept = new Accept;
        $accept->addMediaType('application/json');
        
        $request = $this->getRequest();
        
        $request->setMethod('GET')
            ->getHeaders()->addHeaders([
                $accept,
                Origin::fromString('Origin: http://tesla.zoopcommerce.local'), 
                Host::fromString('Host: tesla.zoopcommerce.local'),
                ContentType::fromString('Content-type: application/json'),
                GenericHeader::fromString('Authorization: Basic ' . base64_encode(sprintf('%s:%s', $key, $secret)))
        ]);
        
        $this->dispatch('http://tesla.zoopcommerce.local/users');
        $response = $this->getResponse();
        
//        $this->assertTrue($response);
    }
}
