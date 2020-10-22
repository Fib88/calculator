<?php
declare(strict_types=1);


ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


function whatIsHappening()
{
    echo '<h2>$_GET</h2>';
    var_dump($_GET);
    echo '<h2>$_POST</h2>';
    var_dump($_POST);
    echo '<h2>$_COOKIE</h2>';
    var_dump($_COOKIE);
    //echo '<h2>$_SESSION</h2>';
    //var_dump($_SESSION);
}

//Using whatIsHappening function from order-form exercise to debug a bit.
whatIsHappening();

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

    public function findGroup($id)
    {
        $pdo = $this->openConnection();
        $handle = $pdo->prepare('SELECT * FROM customer_group WHERE id = :id ');
        $handle->bindValue(':id', $id);
        $handle->execute();
        return $handle->fetch();

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


        if (isset ($_POST['dropdown'])) {
            $customerInfo = $_POST['dropdown'];
        } else {
            $_POST['dropdown'] = $names[0]['firstname'] . " " . $names[0]['lastname'];
        }
        //var_dump($customerInfo);

        if (isset($_POST['dropdown2'])) {
            $productInfo = $_POST['dropdown2'];
        } else {
            $_POST['dropdown2'] = $rows[0]['name'];
        }
        /*
        $ProductSelection = substr($productInfo, strpos($productInfo, "€") + 1);
        echo $ProductSelection;
        */
        if (!isset($customerInfo)) {
            $customerInfo = " ";
        }
        $customername = explode(" ", $customerInfo);

        $pdo = $this->openConnection();
        //split $customer info on space, keep both names
        $handle = $pdo->prepare('SELECT  * FROM customer WHERE firstname LIKE :firstname && lastname LIKE :lastname ');
        $handle->bindValue(':firstname', $customername[0]);
        $handle->bindValue(':lastname', $customername[1]);

        $handle->execute();
        $SelectedCustomer = $handle->fetchAll();
        var_dump($SelectedCustomer);
        if (!empty($SelectedCustomer)) {
            $GroupID = $SelectedCustomer[0]['group_id'];
            $fixedDiscount = $SelectedCustomer[0]['fixed_discount'];
            var_dump("fixed discount" . $fixedDiscount);

            $pdo = $this->openConnection();
            $handle = $pdo->prepare('SELECT * FROM customer_group WHERE id LIKE :id ');
            $handle->bindValue(':id',$GroupID);
            $handle->execute();
            $CustomerGroupSelect = $handle->fetchAll();

            var_dump($CustomerGroupSelect);
        }

        //add customer fixed discount with the discount from the groups

        $allGroups = array();
        array_unshift($allGroups, $this->findGroup($GroupID));
        var_dump("GroupID" . $GroupID);

        while ($allGroups[0]['parent_id'] !== null) {
            array_unshift($allGroups, $this->findGroup($allGroups[0]['parent_id']));
        }
        var_dump($allGroups);

        $fixedDiscountList = [];
        for ($i = 0; $i < count($allGroups); $i++) {
            if ($allGroups[$i]["fixed_discount"] !== null) {
                array_push($fixedDiscountList, (int)$allGroups[$i]["fixed_discount"]);
            }

        }

        var_dump($fixedDiscountList);
       


        require 'View/homepage.php';
    }
}
