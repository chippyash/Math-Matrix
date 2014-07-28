<?php

namespace chippyash\Test\Math\Matrix\Computation\Mul;

use chippyash\Math\Matrix\Computation\Mul\Matrix as CMatrix;
use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Type\Number\IntType;

/**
 */
class MatrixTest extends \PHPUnit_Framework_TestCase
{

    protected $object;
    protected $empty;
    protected $rowVector;
    protected $colVector;
    protected $deepColVector;
    protected $square;
    protected $bigSquare;
    protected $single;
    protected $wideRectangle;
    protected $vwideRectangle;
    protected $longRectangle;
    protected $vlongRectangle;

    protected function setUp()
    {
        $this->object = new CMatrix();
        $this->empty = new NumericMatrix([]);
        $this->rowVector = new NumericMatrix(
                [
            [1, 2, 3]]);
        $this->colVector = new NumericMatrix(
                [
            [1],
            [2],
            [3]]);
        $this->deepColVector = new NumericMatrix(
                [
            [1],
            [2],
            [3],
            [4],
            [5]]);
        $this->square = new NumericMatrix(
                [
            [1, 2, 3],
            [1, 2, 3],
            [1, 2, 3]]);
        $this->bigSquare = new NumericMatrix(
                [
            [1, 2, 3, 4, 5],
            [1, 2, 3, 4, 5],
            [1, 2, 3, 4, 5],
            [1, 2, 3, 4, 5],
            [1, 2, 3, 4, 5]]);
        $this->single = new NumericMatrix([1]);
        $this->wideRectangle = new NumericMatrix(
                [
            [1, 2, 3],
            [1, 2, 3]]);
        $this->vwideRectangle = new NumericMatrix(
                [
            [1, 2, 3, 4, 5, 6],
            [1, 2, 3, 4, 5, 6]]);
        $this->longRectangle = new NumericMatrix(
                [
            [1, 2],
            [1, 2],
            [1, 2]]);
        $this->vlongRectangle = new NumericMatrix(
                [
            [1, 2],
            [1, 2],
            [1, 2],
            [1, 2],
            [1, 2],
            [1, 2]]);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\MatrixException
     * @expectedExceptionMessage Parameter is not a matrix
     */
    public function testComputeRejectsSecondParamNotBeingMatrix()
    {
        $this->object->compute($this->empty, 'foo');
    }

    public function testComputeOnlyAcceptsMatrixValues()
    {
        $this->assertInstanceOf(
                '\chippyash\Math\Matrix\NumericMatrix', $this->object->compute(
                        $this->empty, $this->empty));
    }

    public function testComputeReturnsEmptyIfMatrixIsEmpty()
    {
        $test = $this->object->compute($this->empty, new NumericMatrix([1]));
        $this->assertTrue($test->is('empty'));
        $test = $this->object->compute(new NumericMatrix([1]), $this->empty);
        $this->assertTrue($test->is('empty'));
    }

    /**
     * @expectedException chippyash\Math\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Two matrices cannot be multiplied: mA->columns != mB->rows
     */
    public function testRowVectorXColumnVectorThrowsExceptionIfMatricesIncompatible()
    {
        $colV = new NumericMatrix([[1], [2]]);
        $this->object->compute($this->rowVector, $colV);
    }

    /**
     * @expectedException chippyash\Math\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Two matrices cannot be multiplied: mA->columns != mB->rows
     */
    public function testColumnVectorXRowVectorThrowsExceptionIfMatricesIncompatible()
    {
        $rowV = new NumericMatrix([[1, 2]]);
        $this->object->compute($rowV, $this->colVector);
    }

    public function testSingleItemMatricesReturnSingleItemProduct()
    {
        $this->assertTrue($this->object->compute($this->single, $this->single)->is('singleitem'));
    }

    public function testRowVectorXColVectorReturnsSingleItemMatrix()
    {
        $test = $this->object->compute($this->rowVector, $this->colVector);

        $this->assertEquals(1, $test->rows());
        $this->assertEquals(1, $test->columns());
        $this->assertFalse($test->is('square'));
        $this->assertEquals(
                $this->toStrongType([[14]]),
                $test->toArray());
    }

    public function testColVectorXRowVectorReturnsSquareMatrixOfCorrectSize()
    {
        $test2 = $this->object->compute($this->colVector, $this->rowVector);

        $this->assertEquals(3, $test2->rows());
        $this->assertEquals(3, $test2->columns());
        $this->assertTrue($test2->is('square'));
        $this->assertEquals(
                $this->toStrongType([[1, 2, 3], [2, 4, 6], [3, 6, 9]]),
                $test2->toArray());
    }

    /**
     * @expectedException chippyash\Math\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Two matrices cannot be multiplied: mA->columns != mB->rows
     */
    public function testSquareMatrixXColumVectorThrowsExceptionIfIncompatibleSizes()
    {
        $colV = new NumericMatrix([[1], [2]]);
        $this->object->compute($this->square, $colV);
    }

    /**
     * @expectedException chippyash\Math\Matrix\Exceptions\UndefinedComputationException
     * @expectedExceptionMessage Computation Error: Undefined computation: columnVector x square
     */
    public function testColumVectorXSquareMatrixThrowsUndefinedComputationException()
    {
        $this->object->compute($this->colVector, $this->square);
    }

    /**
     * @expectedException chippyash\Math\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Two matrices cannot be multiplied: mA->rows != mB->columns
     */
    public function testColumnVectorXRowVectorWithUnmatchedRowsThrowsException()
    {
        $this->object->compute($this->deepColVector, $this->rowVector);
    }

    public function testSquareMatrixXColumnVectorReturnsColumnVector()
    {
        $test = $this->object->compute($this->square, $this->colVector);
        $this->assertTrue($test->is('columnvector'));
        $this->assertEquals(
                $this->toStrongType([[14], [14], [14]]),
                $test->toArray());
    }

    public function testSquareXSquareReturnsSquareMatrix()
    {
        $test = $this->object->compute($this->square, $this->square);
        $this->assertTrue($test->is('square'));
        $this->assertEquals(
                $this->toStrongType([[6, 12, 18], [6, 12, 18], [6, 12, 18]]),
                $test->toArray());
    }

    /**
     * @expectedException chippyash\Math\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Two matrices cannot be multiplied: mA->columns != mB->rows
     */
    public function testProductOfTwoSquareMatricesOfDifferentSizesThrowsException()
    {
        $test = $this->object->compute($this->square, $this->bigSquare);
    }

    /**
     * @expectedException chippyash\Math\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Two matrices cannot be multiplied: mA->columns != mB->rows
     */
    public function testProductOfTwoRectanglesWithMaColsNotEqualMbRowsThrowsExceptionTest1()
    {
        $test = $this->object->compute($this->wideRectangle, $this->vlongRectangle);
    }

    /**
     * @expectedException chippyash\Math\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Two matrices cannot be multiplied: mA->columns != mB->rows
     */
    public function testProductOfTwoRectanglesWithMaColsNotEqualMbRowsThrowsExceptionTest2()
    {
        $test = $this->object->compute($this->vwideRectangle, $this->longRectangle);
    }

    public function testProductOfTwoRectanglesWithMaColsEqualMbRowsReturnsResultTest1()
    {
        $test = $this->object->compute($this->wideRectangle, $this->longRectangle);
        $this->assertTrue($test->is('square'));
        $this->assertEquals(2, $test->rows());
        $this->assertEquals(2, $test->columns());
        $this->assertEquals($this->toStrongType([[6,12],[6,12]]), $test->toArray());
    }

    public function testProductOfTwoRectanglesWithMaColsEqualMbRowsReturnsResultTest2()
    {
        $test = $this->object->compute($this->vlongRectangle, $this->vwideRectangle);
        $this->assertTrue($test->is('square'));
        $this->assertEquals($this->vlongRectangle->rows(), $test->rows());
        $this->assertEquals($this->vwideRectangle->columns(), $test->columns());
        $this->assertEquals($this->toStrongType(
                [
                    [3, 6, 9, 12, 15, 18],
                    [3, 6, 9, 12, 15, 18],
                    [3, 6, 9, 12, 15, 18],
                    [3, 6, 9, 12, 15, 18],
                    [3, 6, 9, 12, 15, 18],
                    [3, 6, 9, 12, 15, 18]]),
                $test->toArray());
    }

    public function testKnownOutputOne()
    {
        $arr = [
            [1, 2, 3, 4, 5],
            [6, 7, 8, 9, 10],
            [11, 12, 13, 14, 15],
            [16, 17, 18, 19, 20],
            [21, 22, 23, 24, 25]
        ];
        $mA = $mB = new NumericMatrix($arr);
        $test = $this->object->compute($mA, $mB);
        $this->assertEquals($this->toStrongType(
                [
                    [215,  230,  245,  260,  275],
                    [490,  530,  570,  610,  650],
                    [765,  830,  895,  960, 1025],
                    [1040, 1130, 1220, 1310, 1400],
                    [1315, 1430, 1545, 1660, 1775]
                ]),
                $test->toArray());
    }

    private function toStrongType(array $values)
    {
        $ret = [];
        foreach ($values as $r => $row) {
            foreach ($row as $c => $item) {
                $ret[$r][$c] = new IntType($item);
            }
        }
        return $ret;
    }
}
