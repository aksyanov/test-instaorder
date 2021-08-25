<?php
require __DIR__ .'/init.php';
return array_merge_recursive(
    require __DIR__ . '/config.common.php',
    require __DIR__ . '/config.web.php',
    file_exists(__DIR__.'/loc/config.web.php') ? require __DIR__ . '/loc/config.web.php' : []
);