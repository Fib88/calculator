<?php


class ProductsLoader extends DatabaseConnection
{
    private array $products;

    /**
     * ProductsLoader constructor.
     * @param array $products
     */


        public function __construct()
    {
        $handle = $this->Connection()->prepare("SELECT * FROM products");
        $handle->execute();
        foreach ($handle->fetchAll() as $product) {
            $products[] = $product['id'];
        }
    }



}