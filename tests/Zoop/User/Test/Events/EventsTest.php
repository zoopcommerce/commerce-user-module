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
        //ensure the event manager "trigger" is executed
        $mockEventManager->expects($this->once())->method('trigger');
        $serviceManager->setService('EventManager', $mockEventManager);
        
        //mock requestsssss
        $mockRequest = $this->getMock('Zend\\Http\\Request');
        $serviceManager->setService('Request', $mockRequest);
        $mockResponse = $this->getMock('Zend\\Http\\PhpEnvironment\\Response');
        $serviceManager->setService('Response', $mockResponse);
        
        //mock application
        $mockApplication = $this->getMock('Zend\\Mvc\\Application', [], [[], $serviceManager]);
        $mockApplication->method('getEventManager')
            ->willReturn($mockEventManager);
        $serviceManager->setService('Application', $mockApplication);

        //mock auth service
        $mockAuthenticationService = $this->getMock('Zoop\\GatewayModule\\AuthenticationService');
        $mockAuthenticationService->method('hasIdentity')
            ->willReturn(true);
        $mockAuthenticationService->method('getIdentity')
            ->willReturn(new User);

        $serviceManager->setService('Zend\Authentication\AuthenticationService', $mockAuthenticationService);

        $userFactory = new UserAbstractFactory;
        $userFactory->canCreateServiceWithName($serviceManager, 'user', 'user');
    }
}
