<?php
namespace chippyash\Test\Math\Matrix\Computation;
use chippyash\Math\Matrix\Computation\Concatenate;
use chippyash\Math\Matrix\Matrix;

/**
 *
 * @author akitson
 */
class ConcatenateTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new Concatenate();
    }


    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Parameter is not a matrix
     */
    public function testComputeThrowsExceptionIfSecondOperandIsNotAMatrix()
    {
        $mA = new Matrix(array(array(1,2),array(1,2)));
        $this->object->compute($mA, 'foo');
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: mA->rows != mB->rows
     */
    public function testComputeThrowsExceptionIfMatricesHaveDifferentRowCount()
    {
        $mA = new Matrix(array(array(1,2),array(1,2)));
        $mB = new Matrix(array(array(1,2),array(1,2),array(1,2)));
        $this->object->compute($mA, $mB);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Matrix parameter not complete
     */
    public function testComputeThrowsExceptionIfFirstOperandIsIncompleteMatrix()
    {
        $mA = new Matrix(array(array(1,2),array(1)));
        $mB = new Matrix(array(array(1,2),array(1,2)));
        $this->object->compute($mA, $mB);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Matrix parameter not complete
     */
    public function testComputeThrowsExceptionIfSecondOperandIsIncompleteMatrix()
    {
        $mA = new Matrix(array(array(1,2),array(1,2)));
        $mB = new Matrix(array(array(1,2),array(1)));
        $this->object->compute($mA, $mB);
    }

    /**
     *
     */
    public function testEmptyMatrixReturnsEmptyMatrix()
    {
        $mA = new Matrix(array());
        $test = $this->object->compute($mA, $mA);
        $this->assertInstanceOf('chippyash\Matrix\Matrix', $test);
        $this->assertTrue($test->is('Empty'));
    }

    /**
     *
     */
    public function testComputeReturnsCorrectResult()
    {
        $mA = new Matrix(array(array(1,2),array(1,2)));
        $mB = new Matrix(array(array(3,4),array(3,4)));
        $result = array(array(1,2,3,4),array(1,2,3,4));
        $this->assertEquals($result, $this->object->compute($mA, $mB)->toArray());
    }

}
