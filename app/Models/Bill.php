<?php namespace app\Models;

use CodeIgniter\Model;
use App\Models\Product;
use App\Models\Currency;

class Bill extends Model
{
    public $products;
    
    public $currencies;
    
    /**
     * @desc define APP available Products & Currencies 
     * (Throw Exception if there is any wrong value entered in the initialization process)
     */
    public function __construct(){
        try {
            $this->products = array(new Product('T-shirt', '10.99'),
                new Product('Pants', '14.99'),
                new Product('Jacket', '19.99'),
                new Product('Shoes', '24.99'));
            
            // Set Discounts
            $this->products[3]->discount_percent = 10;
            $this->products[2]->discount_percent = 50;
            $this->products[2]->discount_condition = array('T-shirt'=> 2);
            
            $this->currencies = array(new Currency('USD', '1', '$'),
                new Currency('EGP', '15.67', 'e£'),
                new Currency('EUR', '0.85', '€'));
        } catch (\CodeIgniter\Exceptions\ConfigException $e) {
            throw new \CodeIgniter\Exceptions\ConfigException();
        }
        
    }
    
    /**
     * @desc Preparing to be able to start generate a new bill
     * @param array $bill_products - array of product names ex: array('T-shirt','Pants')
     * @param string $currency - the currency code ex: USD
     * @return array - Bill details or array of error details if error occures
     */
    public function prepare_bill($bill_products, $currency)
    {
      
        $currency_obj = $this->get_currency_obj($currency);
        if(!$currency_obj){
            return array(FALSE, 'wrong currency code');
        }
        
        return $this->print_bill($bill_products,$currency_obj);
        
    }
    
    
    /**
     * @desc The core function which calculate bill elements 
     * @param array $bill_products - array of product names ex: array('T-shirt','Pants')
     * @param Currency $currency_obj - object of currency (model) class
     * @return array - Bill details or error message as array if error occur while calculation
     */
    public function print_bill($bill_products, $currency_obj){
        $products_count = array_count_values($bill_products);
        
        $total_price = 0;
        $discounts_text = '';
        $total_discount = 0;
        // Search for the user input products in the app predefined products.
        foreach ($bill_products as $bill_product){
            $product_found = FALSE;
            foreach ($this->products as $saved_product){
                if($saved_product->name == $bill_product){
                    $product_found = TRUE;
                    $total_price += $saved_product->price;
                    $discounts = $this->get_product_discount($saved_product, $currency_obj, $products_count);
                    $discounts_text .= $discounts[0];
                    $total_discount += $discounts[1];
                }
            }
            if(!$product_found){
                return array(FALSE, 'wrong product name : '.$bill_product);
            }
        }
        // All user input products has been found, So now calculate the bill elements. 
        $taxes = floatval($total_price * 14) / 100;
        $output = array("Subtotal" => $currency_obj->convert_amount($total_price),
            "Taxes" => $currency_obj->convert_amount($taxes));
        if($discounts_text != ''){
            $output["Discounts"] = $discounts_text;
        }
        
        $output["Total"] = $currency_obj->convert_amount($total_price + $taxes - $total_discount, 4);
        return array(TRUE, $output);
    }
    
    
    /**
     * @desc Search for the user input currency code in the predefined currenices.
     * @param string $currency - user input currency code
     * @return Currency - object of currency (model) class or false if not found.
     */
    public function get_currency_obj($currency){
        foreach ($this->currencies as $saved_currency){
            if($saved_currency->code == $currency){
                $currency_obj = $saved_currency;
            }
        }
        if(!isset($currency_obj)){
            return FALSE;
        }
        return $currency_obj;
    }
    
    
    /**
     * @desc Calculate discount for a product
     * @param Product $product_obj - object of product (model) class
     * @param Currency $currency_obj - object of currency (model) class
     * @param array $products_count - array of the user inputs Product occurence ex: array('T_shirt'=>3)
     * @return array - discount text line that will appear in the bill and a discount amount of money
     */
    public function get_product_discount($product_obj, $currency_obj, $products_count){
        $discount_line = '';
        $discount_amount = 0;
        if(!is_null($product_obj->discount_percent)){
            
            if(!is_null($product_obj->discount_condition)){
                // The product has a condition to apply the discount, So we have to check if this conditon is exist to apply this discount.
                foreach ($product_obj->discount_condition as $key=>$val){
                    if(!isset($products_count[$key]) || $products_count[$key] < $val){
                        // discount condition failed, So there is no discount will be applied.
                        return array('', 0);
                    }
                }
                
            }
            // Passed, So apply the discount
            $discount_amount = $product_obj->discount_amount;
            $discount_line = ' -- ' . $product_obj->discount_percent . '% off '.$product_obj->name.': -' . $currency_obj->convert_amount($product_obj->discount_amount, 3);
            
        }
        return array($discount_line, $discount_amount);
    }
    
    
    
    //--------------------------------------------------------------------
    
    
}