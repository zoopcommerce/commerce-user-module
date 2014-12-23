<?php

namespace Zoop\User\Test\DataModel;

use Zoop\Shard\AccessControl\Events as AccessControlEvents;
use Zoop\GomiModule\DataModel\User;
use Zoop\User\Test\AbstractTest;
use Zoop\User\DataModel\Partner\Admin as PartnerAdmin;
use Zoop\User\Roles;

class PartnerAdminTest extends AbstractTest
{
    public function testCreateFail()
    {
        $dm = self::getDocumentManager();

        $serviceLocator = $this->getApplicationServiceLocator()
            ->get('shard.commerce.servicemanager');

        $eventManager = $dm->getEventManager();
        $eventManager->addEventListener(AccessControlEvents::CREATE_DENIED, $this);

        //set auth user
        $sysUser = new User;
        $sysUser->addRole('test');
        $serviceLocator->setAllowOverride(true);
        $serviceLocator->setService('user', $sysUser);

        $email = 'michael@bigspaceship.com';
        $username = 'bigspaceship';
        $password = 'password1';

        $user = new PartnerAdmin;
        $user->setEmail($email);
        $user->setFirstName('Michael');
        $user->setLastName('Lebowitz');
        $user->setUsername($username);
        $user->setPassword($password);

        $user->addEntity('nestle');
        $user->addEntity('bmw');
        $user->addEntity('youtube');

        $dm->persist($user);
        $dm->flush();
        $dm->clear();

        $this->assertTrue(isset($this->calls[AccessControlEvents::CREATE_DENIED]));
    }

    public function testCreateSuccess()
    {
        $dm = self::getDocumentManager();
        $serviceLocator = $this->getApplicationServiceLocator()
            ->get('shard.commerce.servicemanager');

        //set auth user
        $sysUser = new User;
        $sysUser->addRole(Roles::PARTNER_ADMIN);
        $serviceLocator->setAllowOverride(true);
        $serviceLocator->setService('user', $sysUser);

        $email = 'michael@bigspaceship.com';
        $username = 'bigspaceship';
        $password = 'password1';

        $user = new PartnerAdmin;
        $user->setEmail($email);
        $user->setFirstName('Michael');
        $user->setLastName('Lebowitz');
        $user->setUsername($username);
        $user->setPassword($password);

        $user->addEntity('nestle');
        $user->addEntity('bmw');
        $user->addEntity('youtube');

        $dm->persist($user);
        $dm->flush();
        $dm->clear();
        unset($user);

        $user = $this->getUser($username);

        $this->assertTrue($user instanceof PartnerAdmin);
        $this->assertEquals($username, $user->getUsername());
        $this->assertNotEquals($email, $user->getEmail());

        //decrypt the user
        $blockCipherHelper = $serviceLocator->get('crypt.blockcipherhelper');
        $blockCipherHelper->decryptDocument($user, $dm->getClassMetadata(get_class($user)));

        $this->assertEquals($email, $user->getEmail());
        $this->assertNotEquals($password, $user->getPassword());
        $dm->clear();
    }
}
