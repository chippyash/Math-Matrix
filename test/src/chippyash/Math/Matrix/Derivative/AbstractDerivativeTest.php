<?php
namespace chippyash\Test\Math\Matrix\Derivative;

use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Type\Number\IntType;

/**
 *
 * @author akitson
 */
class AbstractDerivativeTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = $this->getMockForAbstractClass('chippyash\Math\Matrix\Derivative\AbstractDerivative');
        $this->object->expects($this->any())
                ->method('derive')
                ->will($this->returnValue(new NumericMatrix([2])));
    }

    /**
     * @expectedException chippyash\Math\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Invoke method expects 0<n<3 arguments
     */
    public function testInvokeExpectsAtLeastOneArgument()
    {
        $f = $this->object;
        $f();
    }

    /**
     * @expectedException chippyash\Math\Matrix\Exceptions\ComputationException
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
        $f(new NumericMatrix([]),'bar');
    }

    public function testInvokeProxiesToDerive()
    {
        $f = $this->object;
        $m = new NumericMatrix([]);
        $this->assertInstanceOf('chippyash\Math\Matrix\NumericMatrix', $f($m));
        $this->assertEquals([[new IntType(2)]], $f($m)->toArray());
    }
}
