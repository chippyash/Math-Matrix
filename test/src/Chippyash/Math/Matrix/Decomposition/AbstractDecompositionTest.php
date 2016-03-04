<?php

namespace Chippyash\Test\Math\Matrix\Decomposition;

use Chippyash\Math\Matrix\Decomposition\AbstractDecomposition;
use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Type\RequiredType;

/**
 * Stub so we can test get() and __get()
 */
class DecompositionStub extends AbstractDecomposition
{

    protected $products = array(
        'foo' => null
    );

    public function decompose(NumericMatrix $mA, $extra = null)
    {
        $this->set('foo', 'bar');
        return $this;
    }

}

/**
 * Stub so we can test get() and __get()
 */
class DecompositionStubWithCallableParameters extends AbstractDecomposition
{

    protected $products = array(
        'foo' => null,
        'bar' => null
    );

    public function decompose(NumericMatrix $mA, $extra = null)
    {
        $this->set('foo', function() {
            return 'baz';
        });
        $this->set('bar', new NumericMatrix(array()));
        return $this;
    }

}

class DecompositionStubSettingUnknownProduct extends AbstractDecomposition
{

    public function decompose(NumericMatrix $mA, $extra = null)
    {
        $this->set('foo', 'bar');
        return $this;
    }

}

/**
 *
 */
class AbstractDecompositionTest extends \PHPUnit_Framework_TestCase
{

    protected $object;

    protected function setUp()
    {
        RequiredType::getInstance()->set(RequiredType::TYPE_NATIVE);
        $this->object = $this->getMockForAbstractClass(
                'Chippyash\Math\Matrix\Decomposition\AbstractDecomposition');
        $this->object->expects($this->any())
                ->method('product')
                ->will($this->returnValue($this->object));
    }

    public function testDecomposeReturnsFluentInterface()
    {
        $this->object->expects($this->any())
                ->method('decompose')
                ->will($this->returnValue($this->object));
        $this->assertInstanceOf(
                get_class($this->object),
                $this->object->decompose(new NumericMatrix([[1, 2], [1, 2]])));
    }

    public function testInvokeProxiesToDecomposeWithOneParameter()
    {
        $this->object->expects($this->any())
                ->method('decompose')
                ->will($this->returnValue($this->object));
        $d = $this->object;
        $m = new NumericMatrix([[1, 2], [1, 2]]);
        $this->assertInstanceOf(get_class($this->object), $d($m));
    }

    public function testInvokeProxiesToDecomposeWithTwoParameters()
    {
        $this->object->expects($this->any())
                ->method('decompose')
                ->will($this->returnValue($this->object));
        $d = $this->object;
        $m = new NumericMatrix([[1, 2], [1, 2]]);
        $this->assertInstanceOf(get_class($this->object), $d($m, 'foo'));
    }

    /**
     * @expectedException Chippyash\Math\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Invoke method expects 0<n<3 arguments
     */
    public function testInvokeRequiresMaximumTwoParameter()
    {
        $this->object->expects($this->any())
                ->method('decompose')
                ->will($this->returnValue($this->object));
        $d = $this->object;
        $this->assertInstanceOf(get_class($this->object), $d('foo','bar','baz'));
    }

    /**
     * @expectedException Chippyash\Math\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Invoke method expects 0<n<3 arguments
     */
    public function testInvokeRequiresMinimumOneParameter()
    {
        $this->object->expects($this->any())
                ->method('decompose')
                ->will($this->returnValue($this->object));
        $d = $this->object;
        $this->assertInstanceOf(get_class($this->object), $d());
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage unknown
     */
    public function testGettingInvalidProductThrowsException()
    {
        $d = new DecompositionStub();
        $d(new NumericMatrix([[1, 2], [1, 2]]))->product('unknown');
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage unknown
     */
    public function testMagicGettingInvalidProductThrowsException()
    {
        $d = new DecompositionStub();
        $d(new NumericMatrix([[1, 2], [1, 2]]))->unknown;
    }

    public function testGettingValidProductReturnsAValue()
    {
        $d = new DecompositionStub();
        $this->assertEquals('bar',
                $d(new NumericMatrix([[1, 2], [1, 2]]))->product('foo'));
    }

    public function testMagicGettingValidProductReturnsAValue()
    {
        $d = new DecompositionStub();
        $this->assertEquals('bar',
                $d(new NumericMatrix([[1, 2], [1, 2]]))->foo);
    }

    public function testGettingValidProductFromCallableReturnsAValue()
    {
        $d = new DecompositionStubWithCallableParameters();
        $this->assertEquals('baz',
                $d(new NumericMatrix([[1, 2], [1, 2]]))->product('foo'));
    }

    public function testMagicGettingValidProductFromCallableReturnsAValue()
    {
        $d = new DecompositionStubWithCallableParameters();
        $this->assertEquals('baz',
                $d(new NumericMatrix([[1, 2], [1, 2]]))->foo);
    }

    public function testGettingInvokableClassProductFromCallableReturnsTheClass()
    {
        $d = new DecompositionStubWithCallableParameters();
        $this->assertInstanceOf(
                'Chippyash\Matrix\Matrix',
                $d(new NumericMatrix([[1, 2], [1, 2]]))->product('bar'));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage foo
     */
    public function testSettingUnknownProductThrowsException()
    {
        $o = new DecompositionStubSettingUnknownProduct();
        $o->decompose(new NumericMatrix(array(1)));
    }

}
