<?php

namespace app\components\api\instaorder\exceptions;

use yii\base\UserException;

class InstaOrderExceptions extends UserException
{
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getName(): string
    {
        return 'InstaOrderExceptions exception';
    }
}
