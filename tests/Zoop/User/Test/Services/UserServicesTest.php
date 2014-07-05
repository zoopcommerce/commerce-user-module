<?php

namespace Zoop\User\Test\Services;

use Zoop\User\Test\AbstractTest;
use Zoop\User\DataModel\Guest;

class UserServicesTest extends AbstractTest
{
    /**
     * @runInSeparateProcess
     */
    public function testNoCookieSet()
    {
        $user = $this->getApplicationServiceLocator()
            ->get('zoop.user.active');
        
        $this->assertTrue($user instanceof Guest);
    }
}
