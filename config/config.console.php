<?php

/* @var $params [] */
/* @var $db [] */

return [
    'id' => 'basic-console',
    'bootstrap' => [
        //'queue',
    ],
    'controllerNamespace' => 'app\commands',
    'language' => 'ru-RU',
    'controllerMap' => [
        /*'migration' => [
            'class' => 'bizley\migration\controllers\MigrationController',
        ],*/
        /*'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationNamespaces' => [
                'yii\queue\db\migrations',
            ],
        ],*/
    ],
    'components' => [
        'log' => [
            'traceLevel' => 3,
            'targets' => [
                /*[
                    'class' => 'pahanini\log\ConsoleTarget',
                    'levels' => ['error', 'warning', 'trace', 'info'],
                    'categories' => ['console'],
                ],*/
            ],
        ],
    ],
];