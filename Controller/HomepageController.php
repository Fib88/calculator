<?php
declare(strict_types=1);

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
        $handle = $pdo->prepare('SELECT name FROM product ');
        $handle->execute();
        $rows = $handle->fetchAll();

        $pdo = $this->openConnection();
        $handle = $pdo->prepare('SELECT firstname FROM customer ');
        $handle->execute();
        $names = $handle->fetchAll();


        require 'View/homepage.php';

    }


}