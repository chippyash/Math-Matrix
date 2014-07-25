<?php
namespace chippyash\Test\Math\Matrix\Computation\Add;
use chippyash\Math\Matrix\Computation\Add\Matrix as CMatrix;
use chippyash\Math\Matrix\Matrix;

/**
 * Description of MatrixTest
 *
 * @author akitson
 */
class MatrixTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new CMatrix();
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Parameter is not a matrix
     */
    public function testComputeRejectsSecondParamNotBeingMatrix()
    {
        $m = new Matrix(array());
        $p = 'foo';
        $this->object->compute($m, $p);
    }

    public function testComputeOnlyAcceptsMatrixValues()
    {
        $m = new Matrix(array());
        $this->assertInstanceOf('chippyash\Matrix\Matrix', $this->object->compute($m, $m));
    }

    public function testComputeReturnsEmptyIfMatrixIsEmpty()
    {
        $m = new Matrix(array());
        $test = $this->object->compute($m, new Matrix(array()));
        $this->assertTrue($test->is('empty'));
        $test = $this->object->compute(new Matrix(array(1)), $m);
        $this->assertTrue($test->is('empty'));
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Matrix parameter not complete
     */
    public function testComputeThrowsExceptionIfFirstOperandIsIncompleteMatrix()
    {
        $m = new Matrix(array(array(1,2),array(1)));
        $this->object->compute($m, $m);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Matrix parameter not complete
     */
    public function testComputeThrowsExceptionIfSecondOperandIsIncompleteMatrix()
    {
        $mA = new Matrix(array());
        $mB = new Matrix(array(array(1,2),array(1)));
        $this->object->compute($mA, $mB);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: mA->cols != mB->cols
     */
    public function testComputeThrowsExceptionIfBothOperandsNotSameSize()
    {
        $mA = new Matrix(array(1));
        $mB = new Matrix(array(array(1,2),array(2,1)));
        $this->object->compute($mA, $mB);
    }

    /**
     * @dataProvider computeMatrices
     */
    public function testComputeReturnsCorrectResult($op1, $op2, $test)
    {
        $mA = new Matrix($op1);
        $mB = new Matrix($op2);
        $this->assertEquals($test, $this->object->compute($mA, $mB)->toArray());
    }

    public function computeMatrices()
    {
        return array(
            array(
                array(array(1,2,3), array(3,2,1), array(2,1,3)),
                array(array(1,2,3), array(3,2,1), array(2,1,3)),
                array(array(2,4,6), array(6,4,2), array(4,2,6))
            ),
              array(
                array(array(-1.12,2.12,3.12), array(3.12,2.12,1.12), array(2.12,1.12,3.12)),
                array(array(-1.12,2.12,3.12), array(3.12,2.12,1.12), array(2.12,1.12,3.12)),
                array(array(-2.24,4.24,6.24), array(6.24,4.24,2.24), array(4.24,2.24,6.24)),
            ),
            array(
                array(array(true,false,true,false)),
                array(array(true,false,false,true)),
                array(array(2,0,1,1)),
            ),
        );
    }

}
