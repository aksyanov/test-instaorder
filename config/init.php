<?php
$db = require __DIR__ . '/params/db.php';
$db = array_merge(
    $db,
    file_exists(__DIR__.'/loc/db.php')?require __DIR__ . '/loc/db.php':[]
);
$params = array_merge(
    require __DIR__ . '/params/params.php',
    file_exists(__DIR__.'/loc/params.php')?require __DIR__ . '/loc/params.php':[]
);