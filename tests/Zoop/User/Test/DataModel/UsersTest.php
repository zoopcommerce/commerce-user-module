<?php

namespace Zoop\User\Test\DataModel;

use Zoop\User\Test\AbstractTest;
use Zoop\User\DataModel\Zoop\SuperAdmin as ZoopSuperAdmin;

class UsersTest extends AbstractTest
{
    public function testZoopSuperAdmin()
    {
        $email = 'elon@teslamotors.com';
        $password = 'solarcity';
        
        $user = new ZoopSuperAdmin;
        $user->setEmail($email);
        $user->setFirstName('Elon');
        $user->setLastName('Musk');
        $user->setUsername('elonmusk');
        $user->setPassword($password);
        $user->addStore('tesla');
        $user->addStore('spacex');
        
        $this->getDocumentManager()->persist($user);
        $this->getDocumentManager()->flush($user);
        $this->getDocumentManager()->clear($user);
        unset($user);
        
        $user = $this->getUser($email);
        
        $this->assertTrue($user instanceof ZoopSuperAdmin);
        $this->assertEquals($email, $user->getEmail());
        $this->assertNotEquals($password, $user->getPassword());
    }
}
