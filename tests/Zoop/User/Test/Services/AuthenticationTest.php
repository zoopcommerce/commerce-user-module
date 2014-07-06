<?php

namespace Zoop\User\Test\Services;

use \DateTime;
use \DateTimezone;
use Zend\Http\Header\Accept;
use Zend\Http\Header\ContentType;
use Zend\Http\Header\Origin;
use Zend\Http\Header\Host;
use Zend\Http\Header\GenericHeader;
use Zoop\User\Test\AbstractTest;

class AuthenticationTest extends AbstractTest
{
//    public function testNoAuthenticatedUser()
//    {
//        $this->createStore();
//        
//        $accept = new Accept;
//        $accept->addMediaType('application/json');
//        
//        $request = $this->getRequest();
//        
//        $request->setMethod('GET')
//            ->getHeaders()->addHeaders([
//                $accept,
//                Origin::fromString('Origin: http://tesla.zoopcommerce.local'), 
//                Host::fromString('Host: tesla.zoopcommerce.local'),
//                ContentType::fromString('Content-type: application/json'),
//                GenericHeader::fromString('Authorization: Basic ' . base64_encode('zoop:testPassword'))
//            ]);
//        
//        $this->dispatch('http://tesla.zoopcommerce.local/users');
//        $response = $this->getResponse();
//        
////        $this->assertTrue($response);
//    }
    
    public function testAuthenticatedUser()
    {
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
                GenericHeader::fromString('Authorization: Basic ' . base64_encode('zoop:testPassword'))
            ]);
        $this->createUser();
        
        $user = $this->getUser('elonmusk');
        
        $test = 23;
//        $this->dispatch('http://tesla.zoopcommerce.local/users/elonmusk');
//        $response = $this->getResponse();
        
//        $this->assertTrue($response);
    }
}
