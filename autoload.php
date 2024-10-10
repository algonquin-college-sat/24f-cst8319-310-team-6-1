<?php

require __DIR__ . '/sms/vendor/autoload.php';


// Load .env file if it exists
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    //var_dump($dotenv);
}
//die();
?>