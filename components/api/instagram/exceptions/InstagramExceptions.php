<?php

namespace app\components\api\instagram\exceptions;

use yii\base\UserException;

class InstagramExceptions extends UserException
{
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getName(): string
    {
        return 'InstagramExceptions exception';
    }
}
