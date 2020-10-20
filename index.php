<?php
declare(strict_types=1);


ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'config.php';
require 'Controller/HomepageController.php';


echo "Testing testing";


$controller = new HomepageController();
$controller->render($_GET, $_POST);






?>


