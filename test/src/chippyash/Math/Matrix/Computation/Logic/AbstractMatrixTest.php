<?php
namespace chippyash\Test\Math\Matrix\Computation\Logic;
use chippyash\Math\Matrix\Matrix;

/**
 *
 * @author akitson
 */
class AbstractMatrixTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = $this->getMockForAbstractClass('chippyash\Matrix\Computation\Logic\AbstractMatrix');
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Parameter is not a matrix
     */
    public function testComputeThrowsExceptionIfSecondParamNotMatrix()
    {
        $m = new Matrix(array());
        $this->object->compute($m, 'foo');
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Matrix parameter not complete
     */
    public function testComputeThrowsExceptionIfMatrixIsIncomplete()
    {
        $mA = new Matrix(array(array(2,1),array(2)));
        $mB = new Matrix(array(array(1)));
        $this->object->compute($mA, $mB);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Matrix parameter not complete
     */
    public function testComputeThrowsExceptionIfExtraIsIncomplete()
    {
        $mA = new Matrix(array(array(2,1),array(2)));
        $mB = new Matrix(array(array(1)));
        $this->object->compute($mB, $mA);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: mA->rows != mB->rows
     */
    public function testComputeThrowsExceptionIfMatricesAreDissimilar()
    {
        $mA = new Matrix(array(array(2,1),array(2,1)));
        $mB = new Matrix(array(array(1)));
        $this->object->compute($mB, $mA);
    }

    public function testComputeReturnsEmptyIfMatrixIsEmpty()
    {
        $mA = new Matrix(array());
        $mB = new Matrix(array(array(1)));
        $this->assertTrue($this->object->compute($mA, $mB)->is('empty'));
        $this->assertTrue($this->object->compute($mB, $mA)->is('empty'));
    }

    public function testComputeReturnsMatrix()
    {
        $this->object
                ->expects($this->once())
                ->method('doComputation')
                ->will($this->returnValue(new Matrix([true])));
        $m = new Matrix([[1]]);
        $test = $this->object->compute($m,$m);
        $this->assertInstanceOf('chippyash\Matrix\Matrix', $test);
        $this->assertEquals([[true]], $test->toArray());
    }
}
