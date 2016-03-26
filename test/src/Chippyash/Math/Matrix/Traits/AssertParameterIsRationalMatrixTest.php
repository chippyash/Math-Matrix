<?php
namespace Chippyash\Test\Math\Matrix\Traits;
use Chippyash\Math\Matrix\RationalMatrix;
use Chippyash\Math\Matrix\Traits\AssertParameterIsRationalMatrix;

class stubTraitAssertParameterIsRationalMatrix
{
    use AssertParameterIsRationalMatrix;

    public function test($param, $msg = null)
    {
        return (is_null($msg))
                ? $this->assertParameterIsRationalMatrix($param)
                : $this->assertParameterIsRationalMatrix($param, $msg);
    }
}

class AssertParameterIsRationalMatrixTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new stubTraitAssertParameterIsRationalMatrix();
    }

    public function testTestingARationalMatrixReturnsClass()
    {
        $this->assertInstanceOf(
                'Chippyash\Test\Math\Matrix\Traits\stubTraitAssertParameterIsRationalMatrix',
                $this->object->test(new RationalMatrix([[]])));
    }

    /**
     * @expectedException Chippyash\Math\Matrix\Exceptions\ComputationException
     */
    public function testTestingANonRationalMatrixThrowsAnException()
    {
        $this->object->test('foo');
    }

    /**
     * @expectedException Chippyash\Math\Matrix\Exceptions\ComputationException
     */
    public function testTestingANonRationalMatrixThrowsAnExceptionWithUserMessage()
    {
        $this->object->test('foo', 'bar');
    }
}
