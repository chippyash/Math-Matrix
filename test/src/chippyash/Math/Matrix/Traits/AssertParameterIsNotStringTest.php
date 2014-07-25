<?php
namespace chippyash\Test\Math\Matrix\Traits;
use chippyash\Math\Matrix\Traits\AssertParameterIsNotString;

class stubTraitAssertParameterIsNotString
{
    use AssertParameterIsNotString;

    public function test($param, $msg = null)
    {
        return (is_null($msg))
                ? $this->assertParameterIsNotString($param)
                : $this->assertParameterIsNotString($param, $msg);
    }
}

class AssertParameterIsNotStringTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new stubTraitAssertParameterIsNotString();
    }

    /**
     * @covers chippyash\Matrix\Traits\AssertParameterIsNotString::assertParameterIsNotString
     */
    public function testNotStringParamReturnsClass()
    {
        $this->assertInstanceOf(
                'chippyash\Test\Matrix\Traits\stubTraitAssertParameterIsNotString',
                $this->object->test(23));
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Parameter is a string
     * @covers chippyash\Matrix\Traits\AssertParameterIsNotString::assertParameterIsNotString
     */
    public function testStringParamThrowsException()
    {
        $this->object->test('foo');
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: foo
     * @covers chippyash\Matrix\Traits\AssertParameterIsNotString::assertParameterIsNotString
     */
    public function testStringParamThrowsExceptionWithUserMessage()
    {
        $this->object->test('bar', 'foo');
    }
}
