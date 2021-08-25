<?php

/* @var $params [] */
/* @var $db [] */

return [
    'name' => $params['app.name'],
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
    ],
    'timeZone' => 'Europe/Moscow',
    'aliases' => [
        '@logs' => '@app/logs',
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@css.web' => '/css',
    ],
    'sourceLanguage' => 'ru',
    'components' => [
        /*'queue' => [
            'class' => 'yii\queue\db\Queue',
            'as log' => 'yii\queue\LogBehavior',
            'db' => 'db',
            'tableName' => '{{%queue}}',
            'channel' => 'default',
            'mutex' => '\yii\mutex\MysqlMutex',
            'ttr' => 60 * 3,
            'attempts' => 3,
        ],*/
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => $db,
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'fileMap' => [
                        'app'       => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
            ],
        ],
        'httpClient' => [
            'class' => 'yii\httpclient\Client',
            'transport' => 'yii\httpclient\CurlTransport',
            'requestConfig' => [
                'options' => [
                    CURLOPT_FOLLOWLOCATION => true
                ]
            ],
        ],
        'log' => [
            'traceLevel' => 0,
            'targets' => [
                /*[
                    'class' => 'airani\log\TelegramTarget',
                    'levels' => ['error'],
                    'botToken' => $params['telegram.bot.token'],
                    'chatId' => $params['telegram.chat_id.exceptions'],
                    'except' => [
                        'yii\web\HttpException:404',
                        'yii\web\HttpException:400',
                        'app\components\exceptions\SilentException',
                        'custom_errors'
                    ],
                ],*/
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error'],
                    'logFile' => '@logs/errors.log',
                    'except' => [
                        'yii\web\HttpException:404',
                    ],
                    /*'maxLogFiles' => 10,
                    'maxFileSize' => 30240,*/
                ],
            ],
        ],
    ],
    'params' => $params,
];