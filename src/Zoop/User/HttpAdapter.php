<?php

namespace Zoop\User;

use Zoop\GatewayModule\Exception;
use Zend\Authentication\Adapter\Http as ZendHttpAdapter;
use Zend\Authentication\Result;

class HttpAdapter extends ZendHttpAdapter
{
    /**
     * Authenticate
     *
     * @throws Exception\RuntimeException
     * @return Authentication\Result
     */
    public function authenticate()
    {
        if (empty($this->request)) {
            $this->getResponse()->setStatusCode(401);
            throw new Exception\RuntimeException(
                'You do not have permission to access this resource.'
            );
        }

//        if ($this->request->getUri()->getScheme() != 'https') {
//            return new Result(
//                Result::FAILURE_UNCATEGORIZED,
//                array(),
//                array('Http authentication must be over https')
//            );
//        }

        return parent::authenticate();
    }
}
