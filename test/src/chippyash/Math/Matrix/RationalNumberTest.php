<?php
namespace chippyash\Test\Math\Matrix;

use chippyash\Math\Matrix\RationalNumber;

class RationalNumberTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructReturnsRationalNumber()
    {
        $o = new RationalNumber();
        $this->assertInstanceOf('chippyash\Math\Matrix\RationalNumber', $o);
    }
}
