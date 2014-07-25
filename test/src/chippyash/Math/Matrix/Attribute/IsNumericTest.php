<?php
namespace chippyash\Test\Math\Matrix\Attribute;
use chippyash\Math\Matrix\Attribute\IsNumeric;
use chippyash\Math\Matrix\Matrix;

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

    /**
     * @covers chippyash\Matrix\Attribute\IsNumeric::is()
     */
    public function testNonCompleteNumericMatrixReturnsFalse()
    {
        $testBad = array(array(1,0,0), array(0,0), array(0));
        $mA = new Matrix($testBad);
        $this->assertFalse($this->object->is($mA));
    }

    /**
     * @covers chippyash\Matrix\Attribute\IsNumeric::is()
     */
    public function testNonNumericMatrixReturnsFalse()
    {
        $testBad = array(array(1,0,'foo'));
        $mA = new Matrix($testBad);
        $this->assertFalse($this->object->is($mA));
    }

    /**
     * @covers chippyash\Matrix\Attribute\IsNumeric::is()
     */
    public function testNumericMatrixReturnsTrue()
    {
        $testGood = array(array(1,"0",1.345));
        $mA = new Matrix($testGood);
        $this->assertTrue($this->object->is($mA));
    }
}
