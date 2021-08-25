<?php

/* @var $params [] */
/* @var $db [] */

return [
    'bootstrap' => [
        'gii',
        'debug'
    ],
    'modules' => [
        'gii' => [
            'class' => 'yii\gii\Module',
            'allowedIPs' => ['*'],
        ],
        'debug' => [
            'class' => 'yii\debug\Module',
            'allowedIPs' => ['*'],
            'historySize' => 1000,
            /*'checkAccessCallback' => function () {
                $user = Yii::$app->user;
                if (!$user->isGuest) {
                    $user = Yii::$app->user->getIdentity();
                    if ($user != null) {
                        return $user->isAdmin();
                    }
                }
            }*/
        ]
    ],
    'components' => [
        'log' => [
            'traceLevel' => 6,
        ],
    ],
];

