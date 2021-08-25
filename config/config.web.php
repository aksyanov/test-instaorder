<?php

/* @var $params [] */
/* @var $db [] */

return [
    'id' => 'basic',
    'language' => 'ru-RU',
    'homeUrl' => '/',
    'components' => [
        'request' => [
            'cookieValidationKey' => 'Tvppoi2mZR10kJjkmmxpUY3QmitmukUI',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<action>' => 'site/<action>',
                '<action>/<id:\d+>' => 'site/<action>',
                '<controller>/<action>' => '<controller>/<action>',
                '<controller>/<action>/<id:\d+>' => '<controller>/<action>',
            ],
        ],
        'session' => [
            'useCookies' => true,
        ],
    ],
];
