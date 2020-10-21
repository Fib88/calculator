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


        if(isset ($_POST['dropdown'])) {
            $customerInfo = $_POST['dropdown'];
        }else {
            $_POST['dropdown']=$names[0]['firstname']." ".$names[0]['lastname'];
        }
        //var_dump($customerInfo);

        if (isset($_POST['dropdown2'])) {
            $productInfo = $_POST['dropdown2'];
        } else {
            $_POST['dropdown2']=$rows[0]['name'];
        }
        /*
        $ProductSelection = substr($productInfo, strpos($productInfo, "€") + 1);
        echo $ProductSelection;
        */
        if (!isset($customerInfo)){
            $customerInfo= " ";
        }
        $customername= explode(" ", $customerInfo );

        $pdo = $this->openConnection();
        //split $customer info on space, keep both names
        $handle = $pdo->prepare('SELECT  * FROM customer WHERE firstname LIKE :firstname && lastname LIKE :lastname ');
        $handle->bindValue(':firstname', $customername[0]);
        $handle->bindValue(':lastname', $customername[1]);

        $handle->execute();
        $SelectedCustomer = $handle->fetchAll();
        var_dump($SelectedCustomer);
        $GroupID= $SelectedCustomer[0]['group_id'];
        $fixedDiscount= $SelectedCustomer[0]['fixed_discount'];


                $pdo = $this->openConnection();
                $handle = $pdo->prepare('SELECT * FROM customer_group WHERE id LIKE :id ');
                $handle->bindValue(':id' ,$GroupID);
                $handle->execute();
                $CustomerGroupSelect = $handle->fetchAll();

                var_dump($CustomerGroupSelect);


//                while  $CustomerGroupSelect[0]['parent_id']!==null){
//                $fixedDiscount+=$CustomerGroupSelect[0]['fixed_discount'];
//
//                }
//                echo $fixedDiscount;


        //loop over the arrays, if the name matches, get the other attributes, but customername will not match with firstname.
        // as it's a variable made of the results of both cells.
        // if ($customername==$names[i]['firstname']." ".$names[i]['lastname'])




        $customerInfo=$_POST['dropdown'];
        var_dump($customerInfo);
//        var_dump($discount);

        $productInfo=$_POST['dropdown2'];
        //var_dump($names);


        require 'View/homepage.php';
    }
}
