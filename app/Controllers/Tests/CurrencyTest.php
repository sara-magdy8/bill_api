<?php namespace app\Controllers\Tests;

use CodeIgniter\Controller;
use App\Models\Currency;
use App\Libraries\Unittesting;

class CurrencyTest extends Controller
{
    protected $unit_testing;
    function __construct(){
        $this->unit_testing = new Unittesting();
    }
    
    /**
     * @case try to create class object in the right way
     * @expected will accept and no errors/exceptions will be occures
     */
    public function create_obj_success()
    {
        try{
            $currency = new Currency('USD', '1', '$');
            if(assert($currency, 'create object of class currency')) {
                $this->unit_testing->assert_success('create object of class currency in a success way');
            }
        }catch (\Throwable $e) {
            $this->unit_testing->assert_failure('','','','create object of class currency');
        }
    }
    
    /**
     * @case try to create class object in the a wrong way
     * @expected the app will throw an exception
     */
    public function create_obj_fail()
    {
        try{
            $currency = new Currency('USD', '1 USD', '$');
            if(assert($currency, 'create object of class currency in a wrong way')) {
                $this->unit_testing->assert_failure('','','','create object of class currency in a wrong way');
            }
        }catch (\Throwable $e) {
            $this->unit_testing->assert_success('create object of class currency in a wrong way');
        }
    }
    
    /**
     * @case create object of the currency class and try to convert an amount of money from dollar to this currency
     * @expected the app will return the amount of money in this currency and be formatted
     */
    public function test_convert_amount()
    {
        try {
            $currency = new Currency('EGP', '15.67', 'e£');
            $actual_amount = $currency->convert_amount(10);
            $expected_amount  = '156.7 e£';
            if(assert($actual_amount == $expected_amount, 'test convert amount')) {
                $this->unit_testing->assert_success('test convert amount');
            }
        } catch (\Throwable $e) {
            $this->unit_testing->assert_failure('','','','test convert amount');
        }
    }
    
    
}