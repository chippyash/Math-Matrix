<?php
namespace Chippyash\Test\Math\Matrix\Attribute;
use Chippyash\Math\Matrix\Attribute\IsNumeric;
use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Math\Matrix\RationalMatrix;
use Chippyash\Math\Matrix\ComplexMatrix;
use Chippyash\Matrix\Matrix;
use Chippyash\Type\RequiredType;
/**
 */
class IsNumericTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        RequiredType::getInstance()->set(RequiredType::TYPE_NATIVE);
        $this->object = new IsNumeric();
    }

    public function testSutHasAttributeInterface()
    {
        $this->assertInstanceOf(
                'Chippyash\Matrix\Interfaces\AttributeInterface',
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
