<?php

namespace Zoop\User\Test\Service;

use Zoop\GomiModule\DataModel\User;
use Zoop\User\Test\AbstractTest;
use Zoop\User\Service\SystemUserUtil;
use Zoop\User\DataModel\Zoop\Admin as ZoopAdmin;

class SystemUserUtilTest extends AbstractTest
{
    public function testAddSystemUserWithEmptyUserNoRole()
    {
        $mockServiceManager = $this->getMock('Zend\\ServiceManager\\ServiceManager');
        $mockServiceManager->expects($this->once())->method('setAllowOverride');
        $mockServiceManager->expects($this->once())->method('setService');

        $mockServiceManager->method('get')
            ->with('user')
            ->willReturn(null);

        $mockServiceManager->method('getAllowOverride')
            ->willReturn(false);

        $util = new SystemUserUtil;
        $util->setServiceLocator($mockServiceManager);

        $util->addSystemUser();
        $this->assertEquals(SystemUserUtil::SYSTEM_USER, $util->getSystemRole());
        $this->assertTrue($util->getSystemUser() instanceof User);
    }

    public function testAddSystemUserWithEmptyUserDefinedRole()
    {
        $mockServiceManager = $this->getMock('Zend\\ServiceManager\\ServiceManager');
        $mockServiceManager->expects($this->once())->method('setAllowOverride');
        $mockServiceManager->expects($this->once())->method('setService');

        $mockServiceManager->method('get')
            ->with('user')
            ->willReturn(null);

        $mockServiceManager->method('getAllowOverride')
            ->willReturn(false);

        $util = new SystemUserUtil;
        $util->setServiceLocator($mockServiceManager);

        $role = 'test::admin';
        $util->addSystemUser($role);
        $this->assertEquals($role, $util->getSystemRole());
        $this->assertTrue($util->getSystemUser() instanceof User);
    }

    public function testAddSystemUserWithUser()
    {
        $user = new ZoopAdmin;
        $user->setUsername('admin');

        $mockServiceManager = $this->getMock('Zend\\ServiceManager\\ServiceManager');
        $mockServiceManager->expects($this->once())->method('setAllowOverride');
        $mockServiceManager->expects($this->once())->method('setService');

        $mockServiceManager->method('get')
            ->with('user')
            ->willReturn($user);

        $mockServiceManager->method('getAllowOverride')
            ->willReturn(false);

        $util = new SystemUserUtil;
        $util->setServiceLocator($mockServiceManager);

        $util->addSystemUser();
        $this->assertTrue($util->getActiveUser() === $user);
    }

    public function testRemoveSystemUserWithNoUser()
    {
        $role = SystemUserUtil::SYSTEM_USER;
        $user = new User;
        $user->setUsername('system');
        $user->setRoles([$role]);

        $mockServiceManager = $this->getMock('Zend\\ServiceManager\\ServiceManager');
        $mockServiceManager->expects($this->once())->method('setAllowOverride');

        $util = new SystemUserUtil;
        $util->setServiceLocator($mockServiceManager);
        $util->setSystemUser($user);
        $util->setSystemRole($role);

        $util->removeSystemUser();
        $this->assertCount(0, $user->getRoles());
    }

    public function testRemoveSystemUserWithUser()
    {
        $role = SystemUserUtil::SYSTEM_USER;
        $user = new User;
        $user->setUsername('system');
        $user->setRoles([$role]);

        $activeUser = new ZoopAdmin;
        $activeUser->setUsername('admin');

        $mockServiceManager = $this->getMock('Zend\\ServiceManager\\ServiceManager');
        $mockServiceManager->expects($this->exactly(2))->method('setAllowOverride');
        $mockServiceManager->expects($this->once())->method('setService');

        $util = new SystemUserUtil;
        $util->setServiceLocator($mockServiceManager);
        $util->setActiveUser($activeUser);
        $util->setSystemUser($user);
        $util->setSystemRole($role);

        $util->removeSystemUser();
        $this->assertCount(0, $user->getRoles());
    }
}
