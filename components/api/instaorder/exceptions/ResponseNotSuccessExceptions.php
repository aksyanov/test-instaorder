<?php

namespace app\components\api\instaorder\exceptions;

class ResponseNotSuccessExceptions extends InstaOrderExceptions
{
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getName(): string
    {
        return 'ResponseNotSuccessExceptions';
    }
}
