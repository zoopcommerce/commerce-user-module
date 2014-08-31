<?php

namespace Zoop\User\Test\DataModel;

use Zoop\Shard\AccessControl\Events as AccessControlEvents;
use Zoop\GomiModule\DataModel\User;
use Zoop\User\Test\AbstractTest;
use Zoop\User\DataModel\Company\Admin as CompanyAdmin;
use Zoop\User\Roles;

class CompanyAdminTest extends AbstractTest
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

        $email = 'jean-marc@nespresso.com';
        $username = 'nespresso';
        $password = 'password1';

        $user = new CompanyAdmin;
        $user->setEmail($email);
        $user->setFirstName('Jean-Marc');
        $user->setLastName('Duvoisin');
        $user->setUsername($username);
        $user->setPassword($password);
        
        $user->addStore('nespresso-en-us');
        $user->addStore('nespresso-en-au');

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

        $email = 'jean-marc@nespresso.com';
        $username = 'nespresso';
        $password = 'password1';

        $user = new CompanyAdmin;
        $user->setEmail($email);
        $user->setFirstName('Jean-Marc');
        $user->setLastName('Duvoisin');
        $user->setUsername($username);
        $user->setPassword($password);
        
        $user->addStore('nespresso-en-us');
        $user->addStore('nespresso-en-au');

        $dm->persist($user);
        $dm->flush();
        $dm->clear();
        unset($user);

        $user = $this->getUser($username);
        
        $this->assertTrue($user instanceof CompanyAdmin);
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
