<?php

namespace App;

use Exception;

/**
 * Class CsrfException
 * Exception được throw khi CSRF validation thất bại
 */
class CsrfException extends Exception
{
    protected $message = 'CSRF token validation failed';
    protected $code = 403;
}

