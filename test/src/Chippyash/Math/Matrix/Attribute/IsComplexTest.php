<?php
namespace Chippyash\Test\Math\Matrix\Attribute;
use Chippyash\Math\Matrix\Attribute\IsComplex;
use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Math\Matrix\RationalMatrix;
use \Chippyash\Math\Matrix\ComplexMatrix;
use Chippyash\Matrix\Matrix;
use Chippyash\Type\RequiredType;

/**
 */
class IsComplexTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        RequiredType::getInstance()->set(RequiredType::TYPE_NATIVE);
        $this->object = new IsComplex();
    }

    public function testSutHasAttributeInterface()
    {
        $this->assertInstanceOf(
                'Chippyash\Matrix\Interfaces\AttributeInterface',
                $this->object);
    }

    public function testComplexMatrixReturnsTrue()
    {
        $mB = new ComplexMatrix([1]);
        $this->assertTrue($this->object->is($mB));
    }

    public function testNonComplexMatrixReturnsFalse()
    {
        $mA = new Matrix([1]);
        $this->assertFalse($this->object->is($mA));
        $mB = new RationalMatrix([[1,0,0],[10,0,2],[3,0.5,1.5]]);
        $this->assertFalse($this->object->is($mB));
        $mC = new NumericMatrix([1]);
        $this->assertFalse($this->object->is($mC));
    }
}
