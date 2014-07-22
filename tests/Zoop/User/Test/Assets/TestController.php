<?php

namespace Zoop\User\Test\Assets;

use Zend\Mvc\Controller\AbstractActionController;

class TestController extends AbstractActionController
{
    public function indexAction()
    {
        if ($this->identity()) {
            $this->response->setContent('true');
        } else {
            $this->response->setStatusCode(403);
        }

        return $this->response;
    }
}
