<?php

namespace Zoop\User\Test\Services;

use Zend\Http\Header\Accept;
use Zend\Http\Header\ContentType;
use Zend\Http\Header\Origin;
use Zend\Http\Header\Host;
use Zend\Http\Header\GenericHeader;
use Zoop\User\DataModel\ApiCredential;
use Zoop\User\Test\AbstractTest;

class AuthenticationTest extends AbstractTest
{
    public function testAuthenticatedZoopSuperUser()
    {
        $key = 'zoop';
        $secret = 'testPassword';
        $this->createAuthUser($key, $secret);
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
    
    /**
     * Creates a user in another process so we don't trigger
     * a get user auth
     * 
     * @runInSeparateProcess
     */
    protected function createAuthUser($key, $secret)
    {
        $credential1 = new ApiCredential($key, $secret);
        $credential2 = new ApiCredential('random', 'otherkey');
        $this->createUser([$credential1, $credential2]);
    }
}
