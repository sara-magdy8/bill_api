<?php

namespace App\Models;

use \CodeIgniter\Test\CIUnitTestCase;
use App\Models\Product;

class ProductTest extends CIUnitTestCase {

    public function setUp(): void {
        parent::setUp();
    }
    
   

    /**
     * @test
     * case : try to create class object in the right way
     * expected : will accept and no errors/exceptions will be occurred
     */
    public function testObjectCreationInRightWay() {
        $product = new Product('T-shirt', '10.99');
        $this->assertInstanceOf(Product::class, $product);
    }

    /**
     * @test
     * case : try to create class object in the a wrong way
     * expected : the APP will throw an exception
     */
    public function testObjectCreationInWrongWayNotAccepted() {
        $this->expectException(\CodeIgniter\Exceptions\ConfigException::class);
        new Product('T-shirt', '10.99 LE');
    }

    /**
     * @test
     * case : try to create class object and set product discount percentage
     * expected : percentage is retrieved with the right value
     */
    public function testSetDiscountToProductAndGetItsValue() {
        $product = new Product('T-shirt', '10');
        $product->discount_percent = 15;
        $expected_discount_amount = 1.5;
        $this->assertEquals($product->discount_amount, $expected_discount_amount);
    }
    
    
    /**
     * @test
     * case : try to create class object and set product discount percentage in wrong way
     * expected : the APP will throw an exception
     */
    public function testSetDiscountToProductInWrongWayNotAccepted() {
        $this->expectException(\CodeIgniter\Exceptions\ConfigException::class);
        $product = new Product('T-shirt', '10');
        $product->discount_percent = '15F';
       
    }

}
