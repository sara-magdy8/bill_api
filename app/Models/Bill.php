<?php

namespace App\Models;

use CodeIgniter\Model;

class Bill extends Model {

    private $products;
    private $currency;
    private $subtotal;
    private $taxes;
    private $discount;
    private $total;

    function __construct($products, $currency) {
        $this->products = $products;
        $this->currency = $currency;
        $this->calculateBill();
    }

    /**
     * Ability to setting attributes only available for : (products,currency)
     * 
     * @param $name name of the attribute
     * @param $value value to be set to the attribute
     */
    public function __set($name, $value) {
        if ($name == 'products' || $name == 'currency') {
            $this->$name = $value;
            $this->calculateBill();
        }
    }

    public function __get($name) {
        return $this->$name;
    }

    /**
     * set the bill calculated attributes
     */
    private function calculateBill() {
        $this->calculateSubtotal();
        $this->calculateTaxes();
        $this->calculateDiscount();
        $this->calculateTotal();
    }

    /**
     * Calculate Bill subtotal and set the class attribute
     */
    private function calculateSubtotal() {
        $total_price = 0;
        foreach ($this->products as $product) {
            $total_price += $product->price;
        }
        $this->subtotal = $total_price;
    }

    /**
     * Calculate Bill taxes and set the class attribute
     */
    private function calculateTaxes() {
        $this->taxes = floatval($this->subtotal * 14) / 100;
    }

    /**
     * Calculate discount, update the bill total discount amount and construct an array with detailed discount data
     * @return (array) - detailed discount data
     */
    public function calculateDiscount() {
        $products_count = $this->productsCountValues();

        $discount = 0;
        $discount_details = array();

        foreach ($this->products as $product) {
            $product_discount = false;
            if (!is_null($product->discount_percent)) {

                if (!is_null($product->discount_condition)) {
                    // The product has a condition to apply the discount, So we have to check if this conditon is exist to apply this discount.
                    foreach ($product->discount_condition as $key => $val) {
                        if (isset($products_count[$key]) && $products_count[$key] >= $val) {
                            // discount condition passed, So it has discount
                            $product_discount = true;
                        }
                    }
                } else {
                    // Passed, So it has discount
                    $product_discount = true;
                }
            }
            // Apply the discount, if the product has
            if ($product_discount) {
                $discount += $product->discount_amount;
                $discount_amount_formatted = $this->currency->convertAmount($product->discount_amount, 3);
                array_push($discount_details, array('product_name' => $product->name, 'discount_percent' => $product->discount_percent, 'discount_amount' => $discount_amount_formatted));
            }
        }
        $this->discount = $discount;
        return $discount_details;
    }
    
    /**
     * Calculate Bill total amount and set the class attribute
     */
    private function calculateTotal() {
       $this->total = $this->subtotal + $this->taxes - $this->discount;
    }

    /**
     * Convert amount of money to bill currency
     * @param $amount money amount
     * @param $digits The optional number of decimal digits to round to.
     * @return (string) amount of money converted and formatted according to bill currency.
     */
    public function convertToBillCurrency($amount, $digits = 2) {
        return $this->currency->convertAmount($amount, $digits);
    }

    /**
     * calculate the number of occurrences for each product
     * @return associative array with products occurrences
     */
    private function productsCountValues() {
        $count_arr = array();
        foreach ($this->products as $product) {
            if (isset($count_arr[$product->name])) {
                $count_arr[$product->name] ++;
            } else {
                $count_arr[$product->name] = 1;
            }
        }
        return $count_arr;
    }

   
}
