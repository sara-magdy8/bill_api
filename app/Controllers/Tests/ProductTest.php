<?php namespace app\Controllers\Tests;

use CodeIgniter\Controller;
use App\Models\Product;
use App\Libraries\Unittesting;

class ProductTest extends Controller
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
            $product = new Product('T-shirt', '10.99');
            if(assert($product, 'create object of class product')) {
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
            $product = new Product('T-shirt', '10.99 LE');
            if(assert($product, 'create object of class product in a wrong way')) {
                $this->unit_testing->assert_failure('','','','create object of class currency in a wrong way');
            }
        }catch (\Throwable $e) {
            $this->unit_testing->assert_success('create object of class product in a wrong way');
        }
    }
    
    /**
     * @case try to create class object and set product discount percentage
     * @expected percentage is retrieved with the right value
     */
    public function set_discount()
    {
        try{
            $product = new Product('T-shirt', '10');
            $product->discount_percent = 15;
            $expected_discount_amount = 1.5;
            
            if(assert($product->discount_percent == 15 && $product->discount_amount == $expected_discount_amount, 'set product discount percentage')) {
                $this->unit_testing->assert_success('set product discount percentage');
            }
        }catch (\Throwable $e) {
            $this->unit_testing->assert_failure('','','','set product discount percentage');
        }
    }
   
    
    
}