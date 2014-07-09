<?php

namespace Zoop\User\Test\Services;

use Zend\Http\Header\Accept;
use Zend\Http\Header\ContentType;
use Zend\Http\Header\Origin;
use Zend\Http\Header\Host;
use Zend\Http\Header\GenericHeader;
use Zoop\User\Test\AbstractTest;
use Zoop\User\Test\Assets\TestData;

class AuthenticationTest extends AbstractTest
{
    public function testAuthenticatedZoopSuperUser()
    {
        $key = 'zoop';
        $secret = 'commerce';
        
        TestData::createZoopAdminUser(self::getDocumentManager(), self::getDbName());
        
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
        
        //this works :S
        $total = self::getDocumentManager()->createQueryBuilder()
                ->find('Zoop\User\DataModel\AbstractUser')
                 ->getQuery()
                ->execute()
                ->count();
        
        $this->dispatch('http://api.zoopcommerce.local/users');
//        $response = $this->getResponse();
        $test = '';
    }
}
