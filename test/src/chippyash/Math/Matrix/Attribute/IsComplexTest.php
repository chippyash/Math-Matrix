<?php
namespace chippyash\Test\Math\Matrix\Attribute;
use chippyash\Math\Matrix\Attribute\IsComplex;
use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Math\Matrix\RationalMatrix;
use \chippyash\Math\Matrix\ComplexMatrix;
use chippyash\Matrix\Matrix;

/**
 */
class IsComplexTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new IsComplex();
    }

    public function testSutHasAttributeInterface()
    {
        $this->assertInstanceOf(
                'chippyash\Matrix\Interfaces\AttributeInterface',
                $this->object);
    }

    public function testComplexMatrixReturnsTrue()
    {
        $mB = new ComplexMatrix([1]);
        $this->assertTrue($this->object->is($mB));
    }

    public function testNonRationalMatrixReturnsFalse()
    {
        $mA = new Matrix([1]);
        $this->assertFalse($this->object->is($mA));
        $mB = new RationalMatrix([[1,0,0],[10,0,2],[3,0.5,1.5]]);
        $this->assertFalse($this->object->is($mB));
        $mC = new NumericMatrix([1]);
        $this->assertFalse($this->object->is($mC));
    }
}
