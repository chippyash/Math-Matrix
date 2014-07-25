<?php
namespace chippyash\Test\Math\Matrix\Attribute;
use chippyash\Math\Matrix\Attribute\IsEmpty;
use chippyash\Math\Matrix\Matrix;

/**
 */
class IsEmptyTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new IsEmpty();
    }

    public function testSutHasAttributeInterface()
    {
        $this->assertInstanceOf(
                'chippyash\Matrix\Interfaces\AttributeInterface',
                $this->object);
    }

    /**
     * @covers chippyash\Matrix\Attribute\IsEmpty::is()
     */
    public function testEmptyMatrixReturnsTrue()
    {
        $mA = new Matrix([]);
        $this->assertTrue($this->object->is($mA));
    }

    /**
     * @covers chippyash\Matrix\Attribute\IsEmpty::is()
     */
    public function testNonEmptyMatrixReturnsFalse()
    {
        $mA = new Matrix([1]);
        $this->assertFalse($this->object->is($mA));
    }

}
