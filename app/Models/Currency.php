<?php namespace app\Models;

use CodeIgniter\Model;
class Currency extends Model
{
    private $code;
    private $rate;
    private $symbol;
    
    public function __construct($code ,$rate, $symbol){
        
        $this->code = $code;
        if(is_numeric($rate)) {
            $this->rate = $rate;
        } else {
            log_message('error', 'currency rate wrong value'.PHP_EOL);
            throw new \CodeIgniter\Exceptions\ConfigException();
        }
        $this->symbol = $symbol;
    }
    
    public function __get($name){
        return $this->$name;
    }
    
    /**
     * @desc Convert & Format amount of money to the requested currency 
     * @param float $amount - The amount of money
     * @param int $digits - Specifies the number of decimal digits to round to. Default is 2
     * @return string - Converted amount of money
     */
    public function convert_amount($amount, $digits = 2){
        return round(floatval($amount * $this->rate),$digits) . ' ' . $this->symbol;
    }
    
}