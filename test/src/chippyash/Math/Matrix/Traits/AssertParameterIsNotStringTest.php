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

    public function testNotStringParamReturnsClass()
    {
        $this->assertInstanceOf(
                'chippyash\Test\Math\Matrix\Traits\stubTraitAssertParameterIsNotString',
                $this->object->test(23));
    }

    /**
     * @expectedException chippyash\Math\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Parameter is a string
     */
    public function testStringParamThrowsException()
    {
        $this->object->test('foo');
    }

    /**
     * @expectedException chippyash\Math\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: foo
     */
    public function testStringParamThrowsExceptionWithUserMessage()
    {
        $this->object->test('bar', 'foo');
    }
}
