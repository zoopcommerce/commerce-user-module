<?php

namespace Zoop\User\Test\DataModel;

use Zoop\User\Test\AbstractTest;
use Zoop\User\DataModel\Zoop\Admin as ZoopAdmin;

class UsersTest extends AbstractTest
{
    public function testZoopAdmin()
    {
        $email = 'elon@teslamotors.com';
        $username = 'elonmusk';
        $password = 'solarcity';
        
        $user = new ZoopAdmin;
        $user->setEmail($email);
        $user->setFirstName('Elon');
        $user->setLastName('Musk');
        $user->setUsername($username);
        $user->setPassword($password);
        $user->addStore('tesla');
        $user->addStore('spacex');
        
        $this->getDocumentManager()->persist($user);
        $this->getDocumentManager()->flush($user);
        $this->getDocumentManager()->clear($user);
        unset($user);
        
        $user = $this->getUser($username);
        
        $this->assertTrue($user instanceof ZoopAdmin);
        $this->assertEquals($email, $user->getEmail());
        $this->assertNotEquals($password, $user->getPassword());
        $this->getDocumentManager()->clear($user);
    }
}
