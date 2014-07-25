<?php
namespace chippyash\Test\Math\Matrix\Traits;
use chippyash\Math\Matrix\Traits\ConvertPotentialBooleanToInt;

class stubTraitConvertPotentialBooleanToInt
{
    use ConvertPotentialBooleanToInt;

    public function test($param)
    {
        return $this->convertPotentialBooleanToInt($param);
    }
}

class ConvertPotentialBooleanToIntTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new stubTraitConvertPotentialBooleanToInt();
    }

    /**
     * @covers chippyash\Matrix\Traits\ConvertPotentialBooleanToInt::convertPotentialBooleanToInt
     */
    public function testBooleanParamReturnsInt()
    {
        $this->assertEquals(1, $this->object->test(true));
        $this->assertEquals(0, $this->object->test(false));
    }

    /**
     * @covers chippyash\Matrix\Traits\ConvertPotentialBooleanToInt::convertPotentialBooleanToInt
     */
    public function testNonBooleanParamReturnsOriginal()
    {
        $this->assertEquals('foo', $this->object->test('foo'));
        $this->assertEquals(2, $this->object->test(2));
        $this->assertEquals(-14.3, $this->object->test(-14.3));
        $this->assertEquals(null, $this->object->test(null));
        $this->assertEquals([], $this->object->test([]));
    }

}
