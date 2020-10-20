<?php
declare(strict_types=1);


ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require 'config.php';
//require "Model/resources/Products.php";
require "ProductsLoader.php";
echo "Testing testing";


function openConnection() : PDO {

    $driverOptions = [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    $pdo = new PDO('mysql:host='. Config::$dbhost .';dbname='. Config::$db, Config::$dbuser, Config::$dbpass, $driverOptions);
    return $pdo;
}
openConnection();
include "ProductsLoader.php";
$products_data = new Products(5,"zipper",2489);
$products_list = $products_data->getName();

echo "$products_list";



?>


