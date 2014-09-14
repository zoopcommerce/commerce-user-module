<?php

namespace Zoop\User\Test\Events;

use Zend\ServiceManager\ServiceManager;
use Zoop\User\Service\UserAbstractFactory;
use Zoop\User\Test\AbstractTest;
use Zoop\User\DataModel\Zoop\Admin as User;

class EventsTest extends AbstractTest
{
    //TODO: fix this test since we removed the event aware interface
    public function testTriggerUserEvent()
    {
        $serviceManager = new ServiceManager;

        //mock event manager
        $mockEventManager = $this->getMock('Zend\\EventManager\\EventManagerInterface');
        $mockEventManager->expects($this->once())->method('trigger');

        //mock auth service
        $mockAuthenticationService = $this->getMock('Zoop\\GatewayModule\\AuthenticationService');
        $mockAuthenticationService->method('hasIdentity')
            ->willReturn(true);
        $mockAuthenticationService->method('getIdentity')
            ->willReturn(new User);

        $serviceManager->setService('Zend\Authentication\AuthenticationService', $mockAuthenticationService);

        $userFactory = new UserAbstractFactory;
        $userFactory->setEventManager($mockEventManager);
        $userFactory->canCreateServiceWithName($serviceManager, 'user', 'user');
    }
}
