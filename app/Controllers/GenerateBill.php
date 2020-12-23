<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Bill;
use App\Models\Product;
use App\Models\Currency;

class GenerateBill extends Controller {

    private $products;
    private $currencies;

    function __construct() {

        $this->defineAppData();
    }

    /**
     * Main function that generate the bill
     * @param (array) $bill_products  the bill user input products (array with products names)
     * @param (string) $currency_code the bill user input currency code 
     * @return (array) the calculated bill
     */
    public function index($bill_products, $currency_code) {
      //  $bill_products = array('T-shirt', 'T-shirt', 'Shoes', 'Jacket');
       // $currency_code = 'USD';
        $products_objs = $this->getProductsObjects($bill_products);
        $currency_obj = $this->getCurrencyObj($currency_code);
        if (!$products_objs || !$currency_obj) {
            return FALSE;
        }
        $bill = new Bill($products_objs, $currency_obj);

        $discount_details = $bill->calculateDiscount();
        $discount_txt = $this->formatDiscountTxt($discount_details);
        
        $output = array("Subtotal" => $bill->convertToBillCurrency($bill->subtotal),
            "Taxes" => $bill->convertToBillCurrency($bill->taxes));
        if ($discount_txt != '') {
            $output["Discounts"] = $discount_txt;
        }

        $output["Total"] = $bill->convertToBillCurrency($bill->total);
        return $output;
    }

    /**
     * Search for the user input products in the predefined products.
     * @param (array) $bill_products - user input products
     * @return (array) - array of objects of product (model) class or false if not found.
     */
    function getProductsObjects($bill_products) {
        $products_objs = array();
        foreach ($bill_products as $bill_product) {
            $product_found = FALSE;
            foreach ($this->products as $saved_product) {
                if ($saved_product->name == $bill_product) {
                    array_push($products_objs, $saved_product);
                    $product_found = TRUE;
                }
            }
            if (!$product_found) {
                return false;
            }
        }
        return $products_objs;
    }

    /**
     * Search for the user input currency code in the predefined currencies.
     * @param string $currency - user input currency code
     * @return (Currency) - object of currency (model) class or false if not found.
     */
    public function getCurrencyObj($currency) {
        foreach ($this->currencies as $saved_currency) {
            if ($saved_currency->code == $currency) {
                $currency_obj = $saved_currency;
            }
        }
        if (!isset($currency_obj)) {
            return FALSE;
        }
        return $currency_obj;
    }
    
    /**
     * format the discount data to be shown in the bill
     * @return (string) bill discount data in the appropriate format 
     */
    function formatDiscountTxt($discount_details){
        $str = '';
        foreach ($discount_details as $discount){
            $str .= sprintf(' -- %s %% off %s: -%s', $discount['discount_percent'], $discount['product_name'], $discount['discount_amount']);
        }
        return $str;
    }

    /**
     * define APP available Products & Currencies 
     * (Throw Exception if there is any wrong value entered in the initialization process)
     */
    function defineAppData() {
        $this->products = array(new Product('T-shirt', '10.99'),
            new Product('Pants', '14.99'),
            new Product('Jacket', '19.99'),
            new Product('Shoes', '24.99'));

        // Set Discounts
        $this->products[3]->discount_percent = 10;
        $this->products[2]->discount_percent = 50;
        $this->products[2]->discount_condition = array('T-shirt' => 2);

        $this->currencies = array(new Currency('USD', '1', '$'),
            new Currency('EGP', '15.67', 'e£'),
            new Currency('EUR', '0.85', '€'));
    }

}
