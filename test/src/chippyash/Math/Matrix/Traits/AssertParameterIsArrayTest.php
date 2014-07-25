<?php
namespace chippyash\Test\Math\Matrix\Traits;
use chippyash\Math\Matrix\Traits\AssertParameterIsArray;

class stubTraitAssertParameterIsArray
{
    use AssertParameterIsArray;

    public function test($param, $msg = null)
    {
        return (is_null($msg))
                ? $this->assertParameterIsArray($param)
                : $this->assertParameterIsArray($param, $msg);
    }
}

class AssertParameterIsArrayTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new stubTraitAssertParameterIsArray();
    }

    /**
     * @covers chippyash\Matrix\Traits\AssertParameterIsArray::assertParameterIsArray
     */
    public function testArrayParamReturnsClass()
    {
        $this->assertInstanceOf(
                'chippyash\Test\Matrix\Traits\stubTraitAssertParameterIsArray',
                $this->object->test([]));
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Parameter is not an array
     * @covers chippyash\Matrix\Traits\AssertParameterIsArray::assertParameterIsArray
     */
    public function testNotArrayParamThrowsException()
    {
        $this->object->test('foo');
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: foo
     * @covers chippyash\Matrix\Traits\AssertParameterIsArray::assertParameterIsArray
     */
    public function testNotArrayParamThrowsExceptionWithUserMessage()
    {
        $this->object->test('bar', 'foo');
    }
}
