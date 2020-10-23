<?php
declare(strict_types=1);


ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();

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


        if (isset($_POST['dropdown2'])) {
            $productInfo = $_POST['dropdown2'];
        } else {
            $_POST['dropdown2'] = $rows[0]['name'];
        }

        if (!isset($productInfo)) {
            $productInfo = " ";
        }
        //utf 8 apparently takes 3 spaces for €.
        $ProductSelection = substr($productInfo, strpos($productInfo, '€') + strlen('€'));
        //var_dump($ProductSelection);


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

            $fixedDiscountList = [];
            $highVarDiscount = 0;
            for ($i = 0; $i < count($allGroups); $i++) {
                if ($allGroups[$i]["fixed_discount"] !== null) {
                    array_push($fixedDiscountList, (int)$allGroups[$i]["fixed_discount"]);

                }

                if ($allGroups[$i]['variable_discount'] !== null) {
                    array_push($allVarDiscounts, (int)$allGroups[$i]['variable_discount']);
                }
                rsort($allVarDiscounts);

                if (isset($allVarDiscounts[0])) {
                    $groupVarDiscount = $allVarDiscounts[0];
                    if ($groupVarDiscount > $varDiscount) {
                        $highVarDiscount = $groupVarDiscount;
                    } elseif ($groupVarDiscount < $varDiscount) {
                        $highVarDiscount = $varDiscount;
                    }
                }
            }

            $fixedDiscountList = array_sum($fixedDiscountList);
            //var_dump($fixedDiscountList);

            $totalFixedDiscount = $fixedDiscountList + $fixedDiscount;
            //var_dump($totalFixedDiscount);
            //var_dump($allVarDiscounts);

            $varDifference = (float)$ProductSelection / 100 * $highVarDiscount;


            $ValueTotalFixedDiscount = $ProductSelection - $totalFixedDiscount;

            $varDifference = (float)$ValueTotalFixedDiscount / 100 * $highVarDiscount;


            echo "Object ordered, from the " . $productInfo . " " . "You have saved: " . max(round($varDifference, 2),$ProductSelection)." ";
            $LeftoverPrice = $ProductSelection - max(round($varDifference, 2),$ProductSelection);;
            echo "Which resulted in the price of " . round($LeftoverPrice, 2) . '<br>';

            if ($totalFixedDiscount > $varDifference) {
                echo " Your Fixed Discount had the most value";
            } else {
                echo "Your Percentage Discount had the most value";
            }
        }
        require 'View/homepage.php';

    }

}