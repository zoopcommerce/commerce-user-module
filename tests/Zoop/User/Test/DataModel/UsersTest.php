<?php

namespace Zoop\User\Test\DataModel;

use Zoop\User\Test\AbstractTest;
use Zoop\User\DataModel\Zoop\Admin as ZoopAdmin;

class UsersTest extends AbstractTest
{
    public function testZoopAdmin()
    {
        $email = 'josh.stuart@zoopcommerce.com';
        $username = 'joshstuart';
        $password = 'zoop1';
        
        $user = new ZoopAdmin;
        $user->setEmail($email);
        $user->setFirstName('Josh');
        $user->setLastName('Stuart');
        $user->setUsername($username);
        $user->setPassword($password);
        
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
