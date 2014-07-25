<?php
namespace chippyash\Test\Math\Matrix\Transformation;
use chippyash\Math\Matrix\Transformation\Invert;
use chippyash\Math\Matrix\Matrix;

/**
 * Description of InvertTest
 *
 */
class InvertTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new Invert(Invert::METHOD_DET);
    }

    public function testEmptyMatrixReturnsEmptyMatrix()
    {
        $this->assertTrue($this->object->transform(new Matrix([]))->is('empty'));
    }

    public function testSingleItemNoZeroMatrixReturnsSimpleInverseMatrix()
    {
        $this->assertEquals([[0.5]], $this->object->transform(new Matrix([2]))->toArray());
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Division by zero
     */
    public function testSingleItemZeroMatrixThrowsException()
    {
        $this->object->transform(new Matrix([0]));
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Matrix is not square
     */
    public function testComputeThrowsExceptionIfFirstOperandIsNotSquare()
    {
        $m = new Matrix(array(array(1,2),array(1)));
        $this->object->transform($m, $m);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\UndefinedComputationException
     * @expectedExceptionMessage Computation Error: Undefined computation: Unknown Inverse computation method
     */
    public function testUndefinedComputationExceptionThrownForUnknownMethod()
    {
        $obj = new Invert(1000);
        $obj->transform(new Matrix([[2,3],[4,3]]));

    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Can only perform inversion on non singular matrix
     */
    public function testComputeNonInvertibleMatricesThrowsException()
    {
        $mA = new Matrix(array(array(1,-3),array(-2,6)));
        $this->object->transform($mA);
    }

    /**
     * @dataProvider computeMatrices
     */
    public function testComputeWithNoIdentityCheckReturnsCorrectResult($operand, $result)
    {
        $mA = new Matrix($operand);
        $mI = $this->object->transform($mA, false);
        $this->assertEquals($result, $mI->toArray());
    }

    /**
     * @dataProvider computeMatrices
     */
    public function testComputeWithIdentityCheckReturnsCorrectResult($operand, $result)
    {
        $mA = new Matrix($operand);
        $this->object->setDebug();
        $mI = $this->object->transform($mA);
        $this->assertEquals($result, $mI->toArray());
    }

    /**
     *
     * @return array[[operand, result],...]
     */
    public function computeMatrices()
    {
        return [
            [
                [
                    [6,11,9],
                    [12,10,5],
                    [13,2,14]],
                [
                    [-0.099464422341239,0.10405508798776,0.026778882938026],
                    [0.078806426931905,0.025248661055853,-0.059678653404744],
                    [0.081101759755164,-0.10022953328233,0.055087987758225]],
            ],
        ];
    }

}
