<?php
namespace chippyash\Test\Math\Matrix\Derivative;
use chippyash\Math\Matrix\Matrix;

/**
 *
 * @author akitson
 */
class AbstractDerivativeTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = $this->getMockForAbstractClass('chippyash\Matrix\Derivative\AbstractDerivative');
        $this->object->expects($this->any())
                ->method('derive')
                ->will($this->returnValue(new Matrix(array('foo'), false, false, null, false)));
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Invoke method expects 0<n<3 arguments
     */
    public function testInvokeExpectsAtLeastOneArgument()
    {
        $f = $this->object;
        $f();
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Invoke method expects 0<n<3 arguments
     */
    public function testInvokeExpectsLessThanThreeArguments()
    {
        $f = $this->object;
        $f('foo','bar','baz');
    }

    public function testInvokeCanAcceptTwoArguments()
    {
        $f = $this->object;
        $f(new Matrix(array()),'bar');
    }

    public function testInvokeProxiesToDerive()
    {
        $f = $this->object;
        $m = new Matrix(array());
        $this->assertInstanceOf('chippyash\Matrix\Matrix', $f($m));
        $this->assertEquals(array(array('foo')), $f($m)->toArray());
    }
}
