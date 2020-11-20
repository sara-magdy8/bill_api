<?php namespace app\Models;

use CodeIgniter\Model;
class Product extends Model
{
    private $name;
    private $price;
    private $discount_percent;
    private $discount_amount;
    private $discount_condition;
    
    public function __construct($name ,$price){
        
        $this->name = $name;
        if(is_numeric($price)) {
            $this->price = $price;
        } else {
            log_message('error', 'product price wrong value'.PHP_EOL);
            throw new \CodeIgniter\Exceptions\ConfigException();
        }
    }
    
    /**
     * @desc set optional attributes (discounts to the product)
     * @param $name - the name of the attribute
     * @param $value - the value of the attribute
     */
    public function __set($name, $value){
        if($name == 'discount_percent' && is_numeric($value)){
            $this->discount_percent = $value;
            $this->discount_amount = ($value * $this->price) / 100;
        } else if($name == 'discount_condition' && is_array($value)){
            $this->discount_condition = $value;
        } else {
            log_message('error', 'wrong way to set product discount'.PHP_EOL);
            throw new \CodeIgniter\Exceptions\ConfigException();
        }
    }
    
    public function __get($name){
        return $this->$name;
    }
    
}