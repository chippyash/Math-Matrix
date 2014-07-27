<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */

namespace chippyash\Math\Matrix\Computation\Mul;

use chippyash\Math\Matrix\Computation\AbstractComputation;
use chippyash\Matrix\Transformation\Colslice;
use chippyash\Math\Matrix\RationalMatrix as MMatrix;
use chippyash\Math\Matrix\ZeroMatrix as ZMatrix;
use chippyash\Math\Matrix\Exceptions\ComputationException;
use chippyash\Matrix\Exceptions\UndefinedComputationException;
use chippyash\Matrix\Traits\AssertParameterIsMatrix;
use chippyash\Matrix\Traits\AssertMatrixIsComplete;
use chippyash\Math\Matrix\Traits\AssertMatrixIsRational;
use FlorianWolters\Component\Number\Fraction as Rational;

/**
 * Standard multiplication of two matrices
 */
class Matrix extends AbstractComputation
{
    use AssertParameterIsMatrix;
    use AssertMatrixIsComplete;
    use AssertMatrixIsRational;

    /**
     * Multiply two matrices together
     * Boolean values are converted to 0 (false) and 1 (true).  Use the logical
     * computations if required.
     * Only numeric values can be multiplied. Strings cannot be multiplied
     *
     * @param MMatrix $mA First matrix operand - required
     * @param MMatrix $extra Second Matrix operand - required
     *
     * @return MMatrix
     *
     * @throws chippyash/Matrix/Exceptions/ComputationException
     */
    public function compute(MMatrix $mA, $extra = null)
    {
        $this->assertParameterIsMatrix($extra, 'Parameter is not a matrix');

        if ($mA->is('empty') || $extra->is('empty')) {
            return new MMatrix(array());
        }

        $this->assertMatrixIsRational($mA, 'Matrix mA is not rational')
             ->assertMatrixIsRational($extra, 'Parameter is not a rational matrix')
             ->assertMatrixIsComplete($mA, 'Matrix mA is not complete')
             ->assertMatrixIsComplete($extra, 'Parameter is not a complete matrix')
             ->checkCompatibility($mA, $extra);

        $product = $this->doComputation($mA, $extra);

        return $this->massageProduct($mA, $extra, $product);
    }

    /**
     * Carry out the actual multiplication using standard matrix multiplication
     * method.
     *
     * @param \chippyash\Matrix\Matrix $mA
     * @param \chippyash\Matrix\Matrix $mB
     * @return array
     * @throws ComputationException
     */
    protected function doComputation(MMatrix $mA, MMatrix $mB)
    {
        $size = max(array($mA->columns(), $mA->rows(), $mB->columns(), $mB->rows()));
        $mZ = new ZMatrix($size, $size);
        $product = $mZ->toArray();
        $dA = $mA->toArray();
        $dB = $mB->toArray();
        for ($i = 0; $i < $size; $i++) {
            for ($k = 0; $k < $size; $k++) {
                for ($j = 0; $j < $size; $j++) {
                    $a = isset($dA[$i][$k]) ? $dA[$i][$k] : new Rational(0);
                    $b = isset($dB[$k][$j]) ? $dB[$k][$j] : new Rational(0);
                    $product[$i][$j] = $product[$i][$j]->add($a->multiplyBy($b));
                }
            }
        }

        return $product;
    }

    /**
     * Check that multiplication is possible for the two matrices
     *
     * @param \chippyash\Matrix\Matrix $mA
     * @param \chippyash\Matrix\Matrix $mB
     */
    protected function checkCompatibility(MMatrix $mA, MMatrix $mB)
    {
        if ($mA->is('rowvector')) {
            $this->checkRowVectorCompatibility($mA, $mB);
        }
        if ($mA->is('columnvector')) {
            $this->checkColumnVectorCompatibility($mA, $mB);
        }
        if ($mA->is('square')) {
            $this->checkSquareMatrixCompatibility($mA, $mB);
        }
        if ($mA->is('rectangle')) {
            $this->checkRectangleMatrixCompatibility($mA, $mB);
        }
    }

    /**
     * Check that multiplication is possible when mA is a row vector
     *
     * @param \chippyash\Matrix\Matrix $mRV
     * @param \chippyash\Matrix\Matrix $mB
     * @throws ComputationException
     */
    protected function checkRowVectorCompatibility(MMatrix $mRV, MMatrix $mB)
    {
        if ($mB->is('columnvector') && ($mRV->columns() != $mB->rows())) {
            throw new ComputationException('Two matrices cannot be multiplied: mA->columns != mB->rows');
        }
    }

    /**
     * Check that multiplication is possible when mA is a column vector
     *
     * @param \chippyash\Matrix\Matrix $mCV
     * @param \chippyash\Matrix\Matrix $mB
     * @throws ComputationException
     * @throws UndefinedComputationException
     */
    protected function checkColumnVectorCompatibility(MMatrix $mCV, MMatrix $mB)
    {
        if ($mB->is('rowvector') && ($mCV->rows() != $mB->columns())) {
            throw new ComputationException('Two matrices cannot be multiplied: mA->rows != mB->columns');
        }
        if ($mB->is('square')) {
            throw new UndefinedComputationException('columnVector x square');
        }
    }

    /**
     * Check that multiplication is possible when mA is a square
     *
     * @param \chippyash\Matrix\Matrix $mSq
     * @param \chippyash\Matrix\Matrix $mB
     * @throws ComputationException
     */
    protected function checkSquareMatrixCompatibility(MMatrix $mSq, MMatrix $mB)
    {
        if ($mB->is('columnvector') && ($mSq->columns() != $mB->rows())) {
            throw new ComputationException('Two matrices cannot be multiplied: mA->columns != mB->rows');
        }
        if ($mB->is('square') && ($mSq->vertices() != $mB->vertices())) {
            throw new ComputationException('Two matrices cannot be multiplied: mA->columns != mB->rows');
        }
    }

    protected function checkRectangleMatrixCompatibility(MMatrix $mA, MMatrix $mB)
    {
        if ($mB->is('rectangle') && ($mA->columns() != $mB->rows())) {
            throw new ComputationException('Two matrices cannot be multiplied: mA->columns != mB->rows');
        }
    }

    /**
     * Massage the product of a multiplication to return a matrix of the
     * correct size and shape
     *
     * @param \chippyash\Matrix\Matrix $mA
     * @param \chippyash\Matrix\Matrix $mB
     * @param array $product
     * @return \chippyash\Matrix\Matrix
     */
    protected function massageProduct(MMatrix $mA, MMatrix $mB, array $product)
    {
        if ($mA->is('rowvector') && $mB->is('columnvector')) {
            return new MMatrix(array($product[0][0]));
        }
        if ($mA->is('square')) {
            return $this->massageSquare($mA, $mB, $product);
        }
        if ($mA->is('rectangle')) {
            return $this->massageRectangle($mA, $mB, $product);
        }
        return new MMatrix($product);
    }

    /**
     * Massage where mA is a square
     * @param \chippyash\Matrix\Matrix $mA
     * @param \chippyash\Matrix\Matrix $mB
     * @param array $product
     * @return \chippyash\Matrix\Matrix
     */
    protected function massageSquare(MMatrix $mA, MMatrix $mB, array $product)
    {
        if ($mB->is('columnvector')) {
            $fC = new Colslice();
            return $fC(new MMatrix($product), array(1, 1));
        }

        return new MMatrix($product);
    }

    /**
     * Massage where mA is rectangle
     * @param \chippyash\Matrix\Matrix $mA
     * @param \chippyash\Matrix\Matrix $mB
     * @param array $product
     * @return \chippyash\Matrix\Matrix
     */
    protected function massageRectangle(MMatrix $mA, MMatrix $mB, array $product)
    {
        if ($mB->is('rectangle') && ($mA->rows() < $mB->rows())) {
            return new MMatrix(array(
                array($product[0][0], $product[0][1]),
                array($product[1][0], $product[1][1])
            ));
        }

        return new MMatrix($product);
    }
}
