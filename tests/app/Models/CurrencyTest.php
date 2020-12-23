<?php

namespace App\Models;

use \CodeIgniter\Test\CIUnitTestCase;
use App\Models\Currency;

class CurrencyTest extends CIUnitTestCase {

    public function setUp(): void {
        parent::setUp();
    }
    
  
    /**
     * @test
     * case : try to create class object in the right way
     * expected : will accept and no errors/exceptions will be occurred
     */
    public function testObjectCreationInRightWay() {
        $currency = new Currency('USD', '0.3', '$');
        $this->assertInstanceOf(Currency::class, $currency);
    }

    /**
     * @test
     * case : try to create class object in the a wrong way
     * expected : the APP will throw an exception
     */
    public function testObjectCreationInWrongWayNotAccepted() {
        $this->expectException(\CodeIgniter\Exceptions\ConfigException::class);
        new Currency('USD', '1.9P', '$');
    }

    /**
     * @test
     * case : create object of the currency class and try to convert an amount of money from dollar to this currency
     * expected : the APP will return the amount of money in this currency and be formatted
     */
    public function testAmountOfMoneyConversionToAnotherCurrency() {
        $currency = new Currency('EGP', '15.67', 'e£');
        $actual_amount = $currency->convertAmount(10,1);
        $expected_amount = '156.7 e£';
        $this->assertEquals($actual_amount, $expected_amount);
    }

}
