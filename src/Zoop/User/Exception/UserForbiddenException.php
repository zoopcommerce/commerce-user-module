<?php

namespace Zoop\User\Exception;

use \Exception;

class UserForbiddenException extends Exception
{
    protected $code = 401;
}
