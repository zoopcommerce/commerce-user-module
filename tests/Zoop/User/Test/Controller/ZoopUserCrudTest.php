<?php

namespace Zoop\User\Test\Controller;

use Zend\Http\Header\Origin;
use Zend\Http\Header\Host;
use Zoop\User\Test\AbstractTest;
use Zoop\Test\Helper\DataHelper;
use Zoop\User\DataModel\Zoop\Admin as ZoopAdmin;
use Zoop\User\DataModel\Partner\Admin as PartnerAdmin;
use Zoop\User\DataModel\Company\Admin as CompanyAdmin;

class ZoopUserCrudTest extends AbstractTest
{
    protected static $zoopAdminKey = 'joshstuart';
    protected static $zoopAdminSecret = 'password1';
    protected static $zoopCreatedAdminKey = 'timroediger';
    protected static $zoopCreatedAdminSecret = 'password2';

    public function testNoAuthorizationCreate()
    {
        $data = [
            "username" => self::$zoopAdminKey,
            "firstName" => "Josh",
            "lastName" => "Stuart",
            "email" => "josh@zoopcommerce.com",
            "password" => self::$zoopAdminSecret,
            "type" => "ZoopAdmin"
        ];

        $request = $this->getRequest();
        $request->setContent(json_encode($data));

        $this->applyJsonRequest($request);

        $request->setMethod('POST')
            ->getHeaders()->addHeaders([
                Origin::fromString('Origin: http://api.zoopcommerce.local'),
                Host::fromString('Host: api.zoopcommerce.local')
            ]);

        $this->dispatch('http://api.zoopcommerce.local/users');
        $response = $this->getResponse();

        //we should change this to a 403
//        $this->assertResponseStatusCode(403);
        $this->assertResponseStatusCode(500);
    }

    public function testCreateZoopUser()
    {
        //create an authorized user
        DataHelper::createZoopUser(self::getDocumentManager(), self::getDbName());

        $data = [
            "username" => self::$zoopCreatedAdminKey,
            "firstName" => "Tim",
            "lastName" => "Roediger",
            "email" => "tim@zoopcommerce.com",
            "password" => self::$zoopCreatedAdminSecret,
            "type" => "zoop::admin"
        ];

        $request = $this->getRequest();
        $request->setContent(json_encode($data));

        $this->applyJsonRequest($request);
        $this->applyUserToRequest($request, self::$zoopAdminKey, self::$zoopAdminSecret);

        $request->setMethod('POST')
            ->getHeaders()->addHeaders([
                Origin::fromString('Origin: http://api.zoopcommerce.local'),
                Host::fromString('Host: api.zoopcommerce.local')
            ]);

        $this->dispatch('http://api.zoopcommerce.local/users');
        $response = $this->getResponse();

        $this->assertResponseStatusCode(201);

        self::getNoAuthDocumentManager()->clear();

        $user = DataHelper::get(
            self::getNoAuthDocumentManager(),
            'Zoop\User\DataModel\AbstractUser',
            self::$zoopCreatedAdminKey
        );
        $this->assertTrue($user instanceof ZoopAdmin);
        $this->assertEquals(self::$zoopCreatedAdminKey, $user->getUsername());

        return self::$zoopCreatedAdminKey;
    }

    public function testCreatePartnerUser()
    {
        $username = "bigspaceship";

        $data = [
            "username" => $username,
            "firstName" => "Michael",
            "lastName" => "Lebowitz",
            "email" => "mike@bigspaceship.com",
            "password" => "password1",
            "merchants" => [
                "nestle",
                "bmw",
                "youtube"
            ],
            "type" => "partner::admin"
        ];

        $request = $this->getRequest();
        $request->setContent(json_encode($data));

        $this->applyJsonRequest($request);
        $this->applyUserToRequest($request, self::$zoopAdminKey, self::$zoopAdminSecret);

        $request->setMethod('POST')
            ->getHeaders()->addHeaders([
                Origin::fromString('Origin: http://api.zoopcommerce.local'),
                Host::fromString('Host: api.zoopcommerce.local')
            ]);

        $this->dispatch('http://api.zoopcommerce.local/users');
        $response = $this->getResponse();

        $this->assertResponseStatusCode(201);

        self::getNoAuthDocumentManager()->clear();

        $user = DataHelper::get(self::getNoAuthDocumentManager(), 'Zoop\User\DataModel\AbstractUser', $username);
        $this->assertTrue($user instanceof PartnerAdmin);
        $this->assertEquals($username, $user->getUsername());

        return $username;
    }

    public function testCreateCompanyUser()
    {
        $username = "nespresso";

        $data = [
            "username" => $username,
            "firstName" => "Jean-Marc",
            "lastName" => "Duvoisin",
            "email" => "jm@nespresso.com",
            "password" => "password1",
            "stores" => [
                "nespresso-en-us",
                "nespresso-en-au"
            ],
            "type" => "company::admin"
        ];

        $request = $this->getRequest();
        $request->setContent(json_encode($data));

        $this->applyJsonRequest($request);
        $this->applyUserToRequest($request, self::$zoopAdminKey, self::$zoopAdminSecret);

        $request->setMethod('POST')
            ->getHeaders()->addHeaders([
                Origin::fromString('Origin: http://api.zoopcommerce.local'),
                Host::fromString('Host: api.zoopcommerce.local')
            ]);

        $this->dispatch('http://api.zoopcommerce.local/users');
        $response = $this->getResponse();

        $this->assertResponseStatusCode(201);

        self::getNoAuthDocumentManager()->clear();

        $user = DataHelper::get(self::getNoAuthDocumentManager(), 'Zoop\User\DataModel\AbstractUser', $username);
        $this->assertTrue($user instanceof CompanyAdmin);
        $this->assertEquals($username, $user->getUsername());

        return $username;
    }

    public function testGetUsers()
    {
        $request = $this->getRequest();
        $this->applyJsonRequest($request);
        $this->applyUserToRequest($request, self::$zoopAdminKey, self::$zoopAdminSecret);

        $request->setMethod('GET')
            ->getHeaders()->addHeaders([
                Origin::fromString('Origin: http://api.zoopcommerce.local'),
                Host::fromString('Host: api.zoopcommerce.local')
            ]);

        $this->dispatch('http://api.zoopcommerce.local/users');
        $response = $this->getResponse();

        $this->assertResponseStatusCode(200);

        $json = $response->getContent();
        $this->assertJson($json);

        $content = json_decode($json, true);

        $this->assertCount(4, $content);

        $user = $content[0];

        $this->assertEquals('joshstuart', $user['username']);
        $this->assertEquals('Josh', $user['firstName']);
        $this->assertEquals('Stuart', $user['lastName']);
        $this->assertEquals('zoop::admin', $user['type']);

        return $user;
    }

    public function testGetUser()
    {
        $request = $this->getRequest();
        $this->applyJsonRequest($request);
        $this->applyUserToRequest($request, self::$zoopAdminKey, self::$zoopAdminSecret);

        $request->setMethod('GET')
            ->getHeaders()->addHeaders([
                Origin::fromString('Origin: http://api.zoopcommerce.local'),
                Host::fromString('Host: api.zoopcommerce.local')
            ]);

        $this->dispatch(sprintf('http://api.zoopcommerce.local/users/%s', self::$zoopCreatedAdminKey));
        $response = $this->getResponse();

        $this->assertResponseStatusCode(200);

        $json = $response->getContent();
        $this->assertJson($json);

        $user = json_decode($json, true);

        $this->assertEquals(self::$zoopCreatedAdminKey, $user['username']);
        $this->assertEquals('Tim', $user['firstName']);
        $this->assertEquals('Roediger', $user['lastName']);
        $this->assertEquals('zoop::admin', $user['type']);

        return $user;
    }

    /**
     * @depends testGetUser
     */
    public function testUpdateUser($userData)
    {
        $username = $userData['username'];
        $data = [
            'firstName' => 'Tim 2',
            'lastName' => 'Roediger 2'
        ];

        $request = $this->getRequest();
        $this->applyJsonRequest($request);
        $this->applyUserToRequest($request, self::$zoopAdminKey, self::$zoopAdminSecret);
        $request->setContent(json_encode($data));

        $request->setMethod('PATCH')
            ->getHeaders()->addHeaders([
                Origin::fromString('Origin: http://api.zoopcommerce.local'),
                Host::fromString('Host: api.zoopcommerce.local')
            ]);

        $this->dispatch(sprintf('http://api.zoopcommerce.local/users/%s', $username));
        $response = $this->getResponse();

        $this->assertResponseStatusCode(204);

        self::getNoAuthDocumentManager()->clear();

        $user = DataHelper::get(self::getNoAuthDocumentManager(), 'Zoop\User\DataModel\AbstractUser', $username);
        $this->assertTrue($user instanceof ZoopAdmin);
        $this->assertEquals($username, $user->getUsername());
        $this->assertEquals('Tim 2', $user->getFirstName());
        $this->assertEquals('Roediger 2', $user->getLastName());
    }

    /**
     * @depends testGetUser
     */
    public function testDeleteUser($userData)
    {
        $username = $userData['username'];

        $request = $this->getRequest();
        $this->applyJsonRequest($request);
        $this->applyUserToRequest($request, self::$zoopAdminKey, self::$zoopAdminSecret);

        $request->setMethod('DELETE')
            ->getHeaders()->addHeaders([
                Origin::fromString('Origin: http://api.zoopcommerce.local'),
                Host::fromString('Host: api.zoopcommerce.local')
            ]);

        $this->dispatch(sprintf('http://api.zoopcommerce.local/users/%s', $username));
        $response = $this->getResponse();

        $this->assertResponseStatusCode(204);

        self::getNoAuthDocumentManager()->clear();

        $user = DataHelper::get(self::getNoAuthDocumentManager(), 'Zoop\User\DataModel\AbstractUser', $username);
        $this->assetEmpty($user);
    }
}
