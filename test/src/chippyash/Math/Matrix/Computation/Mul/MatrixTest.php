<?php

namespace chippyash\Test\Math\Matrix\Computation\Mul;

use chippyash\Math\Matrix\Computation\Mul\Matrix as CMatrix;
use chippyash\Math\Matrix\Matrix;

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
    protected $incomplete;
    protected $single;
    protected $wideRectangle;
    protected $vwideRectangle;
    protected $longRectangle;
    protected $vlongRectangle;
    protected $nonNumeric;

    protected function setUp()
    {
        $this->object = new CMatrix();
        $this->empty = new Matrix(array());
        $this->rowVector = new Matrix(
                array(
            array(1, 2, 3)));
        $this->colVector = new Matrix(
                array(
            array(1),
            array(2),
            array(3)));
        $this->deepColVector = new Matrix(
                array(
            array(1),
            array(2),
            array(3),
            array(4),
            array(5)));
        $this->square = new Matrix(
                array(
            array(1, 2, 3),
            array(1, 2, 3),
            array(1, 2, 3)));
        $this->bigSquare = new Matrix(
                array(
            array(1, 2, 3, 4, 5),
            array(1, 2, 3, 4, 5),
            array(1, 2, 3, 4, 5),
            array(1, 2, 3, 4, 5),
            array(1, 2, 3, 4, 5)));
        $this->incomplete = new Matrix(
                array(
            array(1, 2),
            array(1)));
        $this->single = new Matrix(array(1));
        $this->wideRectangle = new Matrix(
                array(
            array(1, 2, 3),
            array(1, 2, 3)));
        $this->vwideRectangle = new Matrix(
                array(
            array(1, 2, 3, 4, 5, 6),
            array(1, 2, 3, 4, 5, 6)));
        $this->longRectangle = new Matrix(
                array(
            array(1, 2),
            array(1, 2),
            array(1, 2)));
        $this->vlongRectangle = new Matrix(
                array(
            array(1, 2),
            array(1, 2),
            array(1, 2),
            array(1, 2),
            array(1, 2),
            array(1, 2)));
        $this->nonNumeric = new Matrix(['a'], false, false, null, false);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Parameter is not a matrix
     */
    public function testComputeRejectsSecondParamNotBeingMatrix()
    {
        $this->object->compute($this->empty, 'foo');
    }

    public function testComputeOnlyAcceptsMatrixValues()
    {
        $this->assertInstanceOf(
                'chippyash\Matrix\Matrix', $this->object->compute(
                        $this->empty, $this->empty));
    }

    public function testComputeReturnsEmptyIfMatrixIsEmpty()
    {
        $test = $this->object->compute($this->empty, new Matrix(array(1)));
        $this->assertTrue($test->is('empty'));
        $test = $this->object->compute(new Matrix(array(1)), $this->empty);
        $this->assertTrue($test->is('empty'));
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Matrix mA is not complete
     */
    public function testComputeThrowsExceptionIfFirstOperandIsIncompleteMatrix()
    {
        $this->object->compute($this->incomplete, $this->rowVector);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Parameter is not a complete matrix
     */
    public function testComputeThrowsExceptionIfSecondOperandIsIncompleteMatrix()
    {
        $this->object->compute($this->rowVector, $this->incomplete);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Two matrices cannot be multiplied: mA->columns != mB->rows
     */
    public function testRowVectorXColumnVectorThrowsExceptionIfMatricesIncompatible()
    {
        $colV = new Matrix(array(array(1), array(2)));
        $this->object->compute($this->rowVector, $colV);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Two matrices cannot be multiplied: mA->columns != mB->rows
     */
    public function testColumnVectorXRowVectorThrowsExceptionIfMatricesIncompatible()
    {
        $rowV = new Matrix(array(array(1, 2)));
        $this->object->compute($rowV, $this->colVector);
    }

    public function testSingleItemMatricesReturnSingleItemProduct()
    {
        $this->assertTrue($this->object->compute($this->single, $this->single)->is('singleitem'));
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Matrix mA is not rational
     */
    public function testMultiplicationWithNonNumericValuesInFirstMatrixThrowsException()
    {
        $this->object->compute($this->nonNumeric, $this->single);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Parameter is not a rational matrix
     */
    public function testMultiplicationWithNonNumericValuesInSecondMatrixThrowsException()
    {
        $this->object->compute($this->single, $this->nonNumeric);
    }

    public function testRowVectorXColVectorReturnsSingleItemMatrix()
    {
        $test = $this->object->compute($this->rowVector, $this->colVector);

        $this->assertEquals(1, $test->rows());
        $this->assertEquals(1, $test->columns());
        $this->assertFalse($test->is('square'));
        $this->assertEquals(
                array(array(14)), $test->toArray());
    }

    public function testColVectorXRowVectorReturnsSquareMatrixOfCorrectSize()
    {
        $test2 = $this->object->compute($this->colVector, $this->rowVector);

        $this->assertEquals(3, $test2->rows());
        $this->assertEquals(3, $test2->columns());
        $this->assertTrue($test2->is('square'));
        $this->assertEquals(
                array(array(1, 2, 3), array(2, 4, 6), array(3, 6, 9)), $test2->toArray());
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Two matrices cannot be multiplied: mA->columns != mB->rows
     */
    public function testSquareMatrixXColumVectorThrowsExceptionIfIncompatibleSizes()
    {
        $colV = new Matrix(array(array(1), array(2)));
        $this->object->compute($this->square, $colV);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\UndefinedComputationException
     * @expectedExceptionMessage Computation Error: Undefined computation: columnVector x square
     */
    public function testColumVectorXSquareMatrixThrowsUndefinedComputationException()
    {
        $this->object->compute($this->colVector, $this->square);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
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
                array(array(14), array(14), array(14)), $test->toArray());
    }

    public function testSquareXSquareReturnsSquareMatrix()
    {
        $test = $this->object->compute($this->square, $this->square);
        $this->assertTrue($test->is('square'));
        $this->assertEquals(
                array(array(6, 12, 18), array(6, 12, 18), array(6, 12, 18)), $test->toArray());
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Two matrices cannot be multiplied: mA->columns != mB->rows
     */
    public function testProductOfTwoSquareMatricesOfDifferentSizesThrowsException()
    {
        $test = $this->object->compute($this->square, $this->bigSquare);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Two matrices cannot be multiplied: mA->columns != mB->rows
     */
    public function testProductOfTwoRectanglesWithMaColsNotEqualMbRowsThrowsExceptionTest1()
    {
        $test = $this->object->compute($this->wideRectangle, $this->vlongRectangle);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
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
        $this->assertEquals(array(array(6,12),array(6,12)), $test->toArray());
    }

    public function testProductOfTwoRectanglesWithMaColsEqualMbRowsReturnsResultTest2()
    {
        $test = $this->object->compute($this->vlongRectangle, $this->vwideRectangle);
        $this->assertTrue($test->is('square'));
        $this->assertEquals($this->vlongRectangle->rows(), $test->rows());
        $this->assertEquals($this->vwideRectangle->columns(), $test->columns());
        $this->assertEquals(
                array(
                    array(3, 6, 9, 12, 15, 18),
                    array(3, 6, 9, 12, 15, 18),
                    array(3, 6, 9, 12, 15, 18),
                    array(3, 6, 9, 12, 15, 18),
                    array(3, 6, 9, 12, 15, 18),
                    array(3, 6, 9, 12, 15, 18)),
                $test->toArray());
    }

    public function testKnownOutputOne()
    {
        $arr = array(
            array(1, 2, 3, 4, 5),
            array(6, 7, 8, 9, 10),
            array(11, 12, 13, 14, 15),
            array(16, 17, 18, 19, 20),
            array(21, 22, 23, 24, 25)
        );
        $mA = $mB = new Matrix($arr);
        $test = $this->object->compute($mA, $mB);
        $this->assertEquals(
                array(
                    array(215,  230,  245,  260,  275),
                    array(490,  530,  570,  610,  650),
                    array(765,  830,  895,  960, 1025),
                    array(1040, 1130, 1220, 1310, 1400),
                    array(1315, 1430, 1545, 1660, 1775)
                ),
                $test->toArray());
    }

    private function dump(Matrix $mA)
    {
        echo PHP_EOL . $mA->setFormatter(new \chippyash\Matrix\Formatter\Ascii())->display();
    }

}
