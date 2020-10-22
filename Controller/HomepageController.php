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
    public function findGroup($id)
    {
        $connector = new Connection();
        $pdo = $connector->getPdo();
        $handle = $pdo->prepare('SELECT * FROM customer_group WHERE id = :id ');
        $handle->bindValue(':id', $id);
        $handle->execute();
        return $handle->fetch();

    }


    //render function with both $_GET and $_POST vars available if it would be needed.
    public function render(array $GET, array $POST)
    {
        $connector = new Connection();
        $pdo = $connector->getPdo();


        $handle = $pdo->prepare('SELECT name, price FROM product ');
        $handle->execute();
        $rows = $handle->fetchAll();


        $handle = $pdo->prepare('SELECT firstname, lastname FROM customer ');
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

        //utf 8 apparently takes 3 spaces for €.
        $ProductSelection = substr($productInfo, strpos($productInfo, '€') + strlen('€'));
        var_dump($ProductSelection);


        if (!isset($customerInfo)) {
            $customerInfo = " ";
        }
        $customername = explode(" ", $customerInfo);


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


            $varDiscount = $SelectedCustomer[0]['variable_discount'];

            $allGroups = array();
            array_unshift($allGroups, $this->findGroup($GroupID));

            $allVarDiscounts = array();

            while ($allGroups[0]['parent_id'] !== null) {
                array_unshift($allGroups, $this->findGroup($allGroups[0]['parent_id']));


            }
            var_dump($allGroups);

            echo $fixedDiscount . '<br>';
            $fixedDiscountList = [];

            for ($i = 0; $i < count($allGroups); $i++) {
                if ($allGroups[$i]["fixed_discount"] !== null) {
                    array_push($fixedDiscountList, (int)$allGroups[$i]["fixed_discount"]);

                }

                if ($allGroups[$i]['variable_discount'] !== null) {
                    array_push($allVarDiscounts, (int)$allGroups[$i]['variable_discount']);
                }
                rsort($allVarDiscounts);
                $groupVarDiscount = $allVarDiscounts[0];
                if ($groupVarDiscount > $varDiscount) {
                    $highVarDiscount = $groupVarDiscount;
                } elseif ($groupVarDiscount < $varDiscount) {
                    $highVarDiscount = $varDiscount;
                }

            }

            echo $highVarDiscount.'<br>';
            echo (float)($ProductSelection).'<br>';
            $varDifference=(float)$ProductSelection/100*$highVarDiscount;
            echo round($varDifference,2);

        }

        var_dump($allVarDiscounts);
        var_dump($fixedDiscountList);


        require 'View/homepage.php';

    }


}