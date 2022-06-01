<?php
require __DIR__.'./medoo_wrapper.php';

use Medoo\Medoo;
$db = new Medoo([
    'type' => 'mysql',
    'host' => 'localhost',
    'database' => 'db_name',
    'username' => 'db_user',
    'password' => 'db_password'
]);
?>