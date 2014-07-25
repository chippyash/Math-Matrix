<?php
namespace chippyash\Test\Math\Matrix\Computation;
use chippyash\Math\Matrix\Matrix;

/**
 *
 * @author akitson
 */
class AbstractComputationTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = $this->getMockForAbstractClass('chippyash\Matrix\Computation\AbstractComputation');
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
        $f(new Matrix([]),'bar');
    }

    /**
     * @covers chippyash\Matrix\Computation\AbstractComputation::__invoke
     */
    public function testInvokeProxiesToCompute()
    {
        $this->object->expects($this->exactly(2))
                ->method('compute')
                ->will($this->returnValue(new Matrix([['foo']], false, false, null, false)));
        $f = $this->object;
        $m = new Matrix(array());
        $this->assertInstanceOf('chippyash\Matrix\Matrix', $f($m));
        $this->assertEquals([['foo']], $f($m)->toArray());
    }
}
