<?php
namespace chippyash\Test\Math\Matrix\Attribute;
use chippyash\Math\Matrix\Attribute\IsNumeric;
use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Math\Matrix\RationalMatrix;
use chippyash\Math\Matrix\ComplexMatrix;
use chippyash\Matrix\Matrix;

/**
 */
class IsNumericTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new IsNumeric();
    }

    public function testSutHasAttributeInterface()
    {
        $this->assertInstanceOf(
                'chippyash\Matrix\Interfaces\AttributeInterface',
                $this->object);
    }

    public function testNumericMatrixReturnsTrue()
    {
        $mA = new NumericMatrix([[1,0,0],[10,0,2],[3,0.5,1.5]]);
        $this->assertTrue($this->object->is($mA));

        $mC = new RationalMatrix([[1,0,0],[10,0,2],[3,0.5,1.5]]);
        $this->assertTrue($this->object->is($mC));

        $mD = new ComplexMatrix([[1,0,0],[10,0,2],[3,0.5,1.5]]);
        $this->assertTrue($this->object->is($mD));
    }

    public function testNonNumericMatrixReturnsFalse()
    {
        $mA = new Matrix([1]);
        $this->assertFalse($this->object->is($mA));
    }
}
