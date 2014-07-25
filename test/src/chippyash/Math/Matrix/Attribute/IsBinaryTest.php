<?php
namespace chippyash\Test\Math\Matrix\Attribute;
use chippyash\Math\Matrix\Attribute\IsBinary;
use chippyash\Math\Matrix\Matrix;

/**
 */
class IsBinaryTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new IsBinary();
    }

    public function testSutHasAttributeInterface()
    {
        $this->assertInstanceOf(
                'chippyash\Matrix\Interfaces\AttributeInterface',
                $this->object);
    }

    /**
     * @covers chippyash\Matrix\Attribute\IsBinary::is()
     */
    public function testNonCompleteBinaryMatrixReturnsFalse()
    {
        $testBad = array(array(1,0,0), array(0,0), array(0));
        $mA = new Matrix($testBad);
        $this->assertFalse($this->object->is($mA));
    }

    /**
     * @covers chippyash\Matrix\Attribute\IsBinary::is()
     */
    public function testNonBinaryMatrixReturnsFalse()
    {
        $testBad = array(array(1,0,2));
        $mA = new Matrix($testBad);
        $this->assertFalse($this->object->is($mA));
    }

    /**
     * @covers chippyash\Matrix\Attribute\IsBinary::is()
     */
    public function testBinaryMatrixReturnsTrue()
    {
        $testGood = array(array(1,0,1));
        $mA = new Matrix($testGood);
        $this->assertTrue($this->object->is($mA));
    }
}
