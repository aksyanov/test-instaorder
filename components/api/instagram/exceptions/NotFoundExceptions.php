<?php

namespace app\components\api\instagram\exceptions;

class NotFoundExceptions extends InstagramExceptions
{
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getName(): string
    {
        return 'NotFoundExceptions';
    }
}
