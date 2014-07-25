<?php

namespace chippyash\Test\Math\Matrix\Transformation\Decomposition;

use chippyash\Math\Matrix\Transformation\Decomposition\AbstractDecomposition;
use chippyash\Matrix\Matrix;

/**
 * Stub so we can test get() and __get()
 */
class DecompositionStub extends AbstractDecomposition
{

    protected $products = array(
        'foo' => null
    );

    protected function decompose(Matrix $mA)
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

    protected function decompose(Matrix $mA)
    {
        $this->set('foo', function() {
            return 'baz';
        });
        $this->set('bar', new Matrix(array()));
        return $this;
    }

}

class DecompositionStubSettingUnknownProduct extends AbstractDecomposition
{

    protected function decompose(Matrix $mA)
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
        $this->object = $this->getMockForAbstractClass(
                'chippyash\Math\Matrix\Transformation\Decomposition\AbstractDecomposition');
        $this->object->expects($this->any())
                ->method('product')
                ->will($this->returnValue($this->object));
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Matrix parameter is empty
     */
    public function testTransformRejectsEmptyMatrixWithException()
    {
        $this->object->transform(new Matrix(array()));
    }

    /**
     * @covers chippyash\Matrix\Transformation\Decomposition\AbstractDecomposition::transform
     */
    public function testTransformReturnsFluentInterface()
    {
        $this->assertInstanceOf(
                get_class($this->object),
                $this->object->transform(new Matrix([[1, 2], [1, 2]])));
    }

    /**
     * @covers chippyash\Matrix\Transformation\Decomposition\AbstractDecomposition::__invoke
     */
    public function testInvokeProxiesToTransform()
    {
        $d = $this->object;
        $m = new Matrix([[1, 2], [1, 2]]);
        $this->assertInstanceOf(get_class($this->object), $d($m));
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage unknown
     * @covers chippyash\Matrix\Transformation\Decomposition\AbstractDecomposition::product
     */
    public function testGettingInvalidProductThrowsException()
    {
        $d = new DecompositionStub();
        $d(new matrix([[1, 2], [1, 2]]))->product('unknown');
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage unknown
     * @covers chippyash\Matrix\Transformation\Decomposition\AbstractDecomposition::__get
     */
    public function testMagicGettingInvalidProductThrowsException()
    {
        $d = new DecompositionStub();
        $d(new matrix([[1, 2], [1, 2]]))->unknown;
    }

    /**
     * @covers chippyash\Matrix\Transformation\Decomposition\AbstractDecomposition::product
     */
    public function testGettingValidProductReturnsAValue()
    {
        $d = new DecompositionStub();
        $this->assertEquals('bar',
                $d(new matrix([[1, 2], [1, 2]]))->product('foo'));
    }

    /**
     * @covers chippyash\Matrix\Transformation\Decomposition\AbstractDecomposition::__get
     */
    public function testMagicGettingValidProductReturnsAValue()
    {
        $d = new DecompositionStub();
        $this->assertEquals('bar',
                $d(new matrix([[1, 2], [1, 2]]))->foo);
    }

    /**
     * @covers chippyash\Matrix\Transformation\Decomposition\AbstractDecomposition::product
     */
    public function testGettingValidProductFromCallableReturnsAValue()
    {
        $d = new DecompositionStubWithCallableParameters();
        $this->assertEquals('baz',
                $d(new matrix([[1, 2], [1, 2]]))->product('foo'));
    }

    /**
     * @covers chippyash\Matrix\Transformation\Decomposition\AbstractDecomposition::__get
     */
    public function testMagicGettingValidProductFromCallableReturnsAValue()
    {
        $d = new DecompositionStubWithCallableParameters();
        $this->assertEquals('baz',
                $d(new matrix([[1, 2], [1, 2]]))->foo);
    }

    public function testGettingInvokableClassProductFromCallableReturnsTheClass()
    {
        $d = new DecompositionStubWithCallableParameters();
        $this->assertInstanceOf(
                'chippyash\Matrix\Matrix',
                $d(new Matrix([[1, 2], [1, 2]]))->product('bar'));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage foo
     */
    public function testSettingUnknownProductThrowsException()
    {
        $o = new DecompositionStubSettingUnknownProduct();
        $o->transform(new Matrix(array(1)));
    }

}
