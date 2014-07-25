<?php
namespace chippyash\Test\Math\Matrix\Computation;
use chippyash\Math\Matrix\Computation\Rational;
use chippyash\Math\Matrix\Exceptions\UndefinedComputationException;

/**
 *
 * @author akitson
 */
class RationalTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructUsingIntegerParamReturnsRationalClass()
    {
        $o1 = new Rational(1);
        $this->assertInstanceOf('chippyash\Matrix\Computation\Rational', $o1);
        $o2 = new Rational(1, 1);
        $this->assertInstanceOf('chippyash\Matrix\Computation\Rational', $o2);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\UndefinedComputationException
     * @expectedExceptionMessage Rational denominator must not be zero
     */
    public function testIntegerConstructionWithZeroDenominatorThrowsException()
    {
        $o = new Rational(1, 0);
    }

    public function testIntegerConstructionWithNegativeDenominatorReturnsRationalClass()
    {
        $o1 = new Rational(1, -4);
        $this->assertInstanceOf('chippyash\Matrix\Computation\Rational', $o1);
    }

    public function testIntegerConstructionWithNegativeNumeratorReturnsRationalClass()
    {
        $o1 = new Rational(-4, 23);
        $this->assertInstanceOf('chippyash\Matrix\Computation\Rational', $o1);
    }

    public function testConstructUsingFloatParamReturnsRationalClass()
    {
        $o1 = new Rational(1.234);
        $this->assertInstanceOf('chippyash\Matrix\Computation\Rational', $o1);
    }

    public function testConstructUsingNegativeFloatParamReturnsRationalClass()
    {
        $o1 = new Rational(-1.234);
        $this->assertInstanceOf('chippyash\Matrix\Computation\Rational', $o1);
    }

    public function testConstructUsingStringParamReturnsRationalClass()
    {
        $o1 = new Rational("1/34");
        $this->assertInstanceOf('chippyash\Matrix\Computation\Rational', $o1);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\UndefinedComputationException
     * @expectedExceptionMessage Format of Rational string input is invalid
     */
    public function testConstructUsingInvalidStringFormatThrowsException()
    {
        $o = new Rational('45');
    }

    public function testConstructWithNegativeNumeratorStringReturnsRationalClass()
    {
        $o1 = new Rational("-1/34");
        $this->assertInstanceOf('chippyash\Matrix\Computation\Rational', $o1);
        $o1 = new Rational("- 1/34");
        $this->assertInstanceOf('chippyash\Matrix\Computation\Rational', $o1);
    }

    public function testConstructWithNegativeDenominatorStringReturnsRationalClass()
    {
        $o1 = new Rational("1/-34");
        $this->assertInstanceOf('chippyash\Matrix\Computation\Rational', $o1);
        $o1 = new Rational("1/- 34");
        $this->assertInstanceOf('chippyash\Matrix\Computation\Rational', $o1);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\UndefinedComputationException
     * @expectedExceptionMessage Rational expects int, float or string value
     */
    public function testConstructionWithUnsupportedParameterTypeThrowsException()
    {
        $o = new Rational([]);
    }
}
