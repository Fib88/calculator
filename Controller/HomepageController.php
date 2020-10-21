<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

class HomepageController
{

    public function openConnection(): PDO
    {
        // Try to figure out what these should be for you
        $driverOptions = [
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        // Try to understand what happens here
        $pdo = new PDO('mysql:host=' . Config::$dbhost . ';dbname=' . Config::$db, Config::$dbuser, Config::$dbpass, $driverOptions);

        // Why we do this here
        return $pdo;
    }

    //render function with both $_GET and $_POST vars available if it would be needed.
    public function render(array $GET, array $POST)
    {
        $pdo = $this->openConnection();
        $handle = $pdo->prepare('SELECT name, price FROM product ');
        $handle->execute();
        $rows = $handle->fetchAll();

        $pdo = $this->openConnection();
        $handle = $pdo->prepare('SELECT * FROM customer ');
        $handle->execute();
        $names = $handle->fetchAll();

        $pdo = $this->openConnection();
        $handle = $pdo->prepare('SELECT fixed_discount, variable_discount, group_id FROM customer ');
        $handle->execute();
        $discount = $handle->fetchAll();

        function whatIsHappening() {
            echo '<h2>$_GET</h2>';
            var_dump($_GET);
            echo '<h2>$_POST</h2>';
            var_dump($_POST);
            echo '<h2>$_COOKIE</h2>';
            var_dump($_COOKIE);
            echo '<h2>$_SESSION</h2>';
            var_dump($_SESSION);
        }

        $customerInfo=$_POST['dropdown'];
        var_dump($customerInfo);
//        var_dump($discount);

        $productInfo=$_POST['dropdown2'];
        var_dump($names);


        require 'View/homepage.php';
        whatIsHappening();
    }


}
