<?php

/* @var $params [] */
/* @var $db [] */

return [
    'bootstrap' => [
        'gii',
    ],
    'modules' => [
        'gii' => [
            'class' => 'yii\gii\Module',
            'allowedIPs' => ['*'],
        ],
    ],
];