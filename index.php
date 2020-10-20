<?php
declare(strict_types=1);
require 'config.php';
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

echo "Testing testing";

function openConnection() : PDO {
    // Try to figure out what these should be for you


    $driverOptions = [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    // Try to understand what happens here
    $pdo = new PDO('mysql:host='. Config::$dbHost .';dbname='. Config::$db, Config::$dbuser, Config::$dbpass, $driverOptions);

    // Why we do this here
    return $pdo;
}




