<?php
namespace Chippyash\Test\Math\Matrix\Derivative;

use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Type\RequiredType;
use Chippyash\Type\TypeFactory;
/**
 *
 * @author akitson
 */
class AbstractDerivativeTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        RequiredType::getInstance()->set(RequiredType::TYPE_NATIVE);
        $this->object = $this->getMockForAbstractClass('Chippyash\Math\Matrix\Derivative\AbstractDerivative');
        $this->object->expects($this->any())
                ->method('derive')
                ->will($this->returnValue(new NumericMatrix([2])));
    }

    /**
     * @expectedException Chippyash\Math\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Invoke method expects 0<n<3 arguments
     */
    public function testInvokeExpectsAtLeastOneArgument()
    {
        $f = $this->object;
        $f();
    }

    /**
     * @expectedException Chippyash\Math\Matrix\Exceptions\ComputationException
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
        $this->assertInstanceOf('Chippyash\Math\Matrix\NumericMatrix', $f($m));
        $this->assertEquals([[TypeFactory::createInt(2)]], $f($m)->toArray());
    }
}
