<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */

namespace Chippyash\Math\Matrix\Computation\Mul;

use Chippyash\Math\Matrix\Computation\AbstractComputation;
use Chippyash\Matrix\Transformation\Colslice;
use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Math\Matrix\ZeroMatrix as ZMatrix;
use Chippyash\Math\Matrix\Exceptions\ComputationException;
use Chippyash\Math\Matrix\Exceptions\UndefinedComputationException;
use Chippyash\Math\Matrix\Traits\CreateCorrectMatrixType;
use Chippyash\Math\Matrix\Traits\CreateCorrectScalarType;
use Chippyash\Math\Matrix\Traits\AssertMatrixIsNumeric;
use Chippyash\Matrix\Traits\AssertParameterIsMatrix;
use Chippyash\Type\TypeFactory;
use Chippyash\Math\Type\Calculator;

/**
 * Standard multiplication of two matrices
 */
class Matrix extends AbstractComputation
{
    use CreateCorrectMatrixType;
    use CreateCorrectScalarType;
    use AssertMatrixIsNumeric;
    use AssertParameterIsMatrix;

    /**
     * Multiply two matrices together
     * Boolean values are converted to 0 (false) and 1 (true).  Use the logical
     * computations if required.
     * Only numeric values can be multiplied. Strings cannot be multiplied
     *
     * @param \Chippyash\Math\Matrix\NumericMatrix $mA First matrix operand - required
     * @param \Chippyash\Math\Matrix\NumericMatrix $extra Second Matrix operand - required
     *
     * @return \Chippyash\Math\Matrix\NumericMatrix
     *
     * @throws Chippyash/Matrix/Exceptions/ComputationException
     */
    public function compute(NumericMatrix $mA, $extra = null)
    {
        $this->assertParameterIsMatrix($extra, 'Parameter is not a matrix')
                ->assertMatrixIsNumeric($extra, 'Parameter is not a numeric matrix');

        if ($mA->is('empty') || $extra->is('empty')) {
            return $this->createCorrectMatrixType($mA, []);
        }

        $this->checkCompatibility($mA, $extra);

        $product = $this->doComputation($mA, $extra);

        return $this->massageProduct($mA, $extra, $product);
    }

    /**
     * Carry out the actual multiplication using standard matrix multiplication
     * method.
     *
     * @param \Chippyash\Math\Matrix\NumericMatrix $mA
     * @param \Chippyash\Math\Matrix\NumericMatrix $mB
     * @return array
     * @throws ComputationException
     */
    protected function doComputation(NumericMatrix $mA, NumericMatrix $mB)
    {
        $size = TypeFactory::createInt(max(array($mA->columns(), $mA->rows(), $mB->columns(), $mB->rows())));
        $mZ = new ZMatrix($size, $size);
        $size = $size(); //convert back to internal type
        $product = $mZ->toArray();
        $dA = $mA->toArray();
        $dB = $mB->toArray();
        $zero = $this->createCorrectScalarType($mA, 0);
        $calc = new Calculator();
        for ($i = 0; $i < $size; $i++) {
            for ($k = 0; $k < $size; $k++) {
                for ($j = 0; $j < $size; $j++) {
                    $a = isset($dA[$i][$k]) ? $dA[$i][$k] : $zero;
                    $b = isset($dB[$k][$j]) ? $dB[$k][$j] : $zero;
                    $product[$i][$j] = $calc->add($product[$i][$j], $calc->mul($a, $b));
                }
            }
        }

        return $product;
    }

    /**
     * Check that multiplication is possible for the two matrices
     *
     * @param \Chippyash\Math\Matrix\NumericMatrix $mA
     * @param \Chippyash\Math\Matrix\NumericMatrix $mB
     */
    protected function checkCompatibility(NumericMatrix $mA, NumericMatrix $mB)
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
     * @param \Chippyash\Math\Matrix\NumericMatrix $mRV
     * @param \Chippyash\Math\Matrix\NumericMatrix $mB
     * @throws ComputationException
     */
    protected function checkRowVectorCompatibility(NumericMatrix $mRV, NumericMatrix $mB)
    {
        if ($mB->is('columnvector') && ($mRV->columns() != $mB->rows())) {
            throw new ComputationException('Two matrices cannot be multiplied: mA->columns != mB->rows');
        }
    }

    /**
     * Check that multiplication is possible when mA is a column vector
     *
     * @param \Chippyash\Math\Matrix\NumericMatrix $mCV
     * @param \Chippyash\Math\Matrix\NumericMatrix $mB
     * @throws ComputationException
     * @throws UndefinedComputationException
     */
    protected function checkColumnVectorCompatibility(NumericMatrix $mCV, NumericMatrix $mB)
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
     * @param \Chippyash\Math\Matrix\NumericMatrix $mSq
     * @param \Chippyash\Math\Matrix\NumericMatrix $mB
     * @throws ComputationException
     */
    protected function checkSquareMatrixCompatibility(NumericMatrix $mSq, NumericMatrix $mB)
    {
        if ($mB->is('columnvector') && ($mSq->columns() != $mB->rows())) {
            throw new ComputationException('Two matrices cannot be multiplied: mA->columns != mB->rows');
        }
        if ($mB->is('square') && ($mSq->vertices() != $mB->vertices())) {
            throw new ComputationException('Two matrices cannot be multiplied: mA->columns != mB->rows');
        }
    }

    /**
     *
     * @param \Chippyash\Math\Matrix\NumericMatrix $mA
     * @param \Chippyash\Math\Matrix\NumericMatrix $mB
     * @throws ComputationException
     */
    protected function checkRectangleMatrixCompatibility(NumericMatrix $mA, NumericMatrix $mB)
    {
        if ($mB->is('rectangle') && ($mA->columns() != $mB->rows())) {
            throw new ComputationException('Two matrices cannot be multiplied: mA->columns != mB->rows');
        }
    }

    /**
     * Massage the product of a multiplication to return a matrix of the
     * correct size and shape
     *
     * @param \Chippyash\Math\Matrix\NumericMatrix $mA
     * @param \Chippyash\Math\Matrix\NumericMatrix $mB
     * @param array $product
     * @return \Chippyash\Math\Matrix\NumericMatrix
     */
    protected function massageProduct(NumericMatrix $mA, NumericMatrix $mB, array $product)
    {
        if ($mA->is('rowvector') && $mB->is('columnvector')) {
            return $this->createCorrectMatrixType($mA, [$product[0][0]]);
        }
        if ($mA->is('square')) {
            return $this->massageSquare($mA, $mB, $product);
        }
        if ($mA->is('rectangle')) {
            return $this->massageRectangle($mA, $mB, $product);
        }
        return $this->createCorrectMatrixType($mA, $product);
    }

    /**
     * Massage where mA is a square
     * @param \Chippyash\Math\Matrix\NumericMatrix $mA
     * @param \Chippyash\Math\Matrix\NumericMatrix $mB
     * @param array $product
     * @return \Chippyash\Math\Matrix\NumericMatrix
     */
    protected function massageSquare(NumericMatrix $mA, NumericMatrix $mB, array $product)
    {
        if ($mB->is('columnvector')) {
            $fC = new Colslice();
            return $fC($this->createCorrectMatrixType($mA, $product), array(1, 1));
        }

        return $this->createCorrectMatrixType($mA, $product);
    }

    /**
     * Massage where mA is rectangle
     * @param \Chippyash\Math\Matrix\NumericMatrix $mA
     * @param \Chippyash\Math\Matrix\NumericMatrix $mB
     * @param array $product
     * @return \Chippyash\Math\Matrix\NumericMatrix
     */
    protected function massageRectangle(NumericMatrix $mA, NumericMatrix $mB, array $product)
    {
        if ($mB->is('rectangle') && ($mA->rows() < $mB->rows())) {
            return $this->createCorrectMatrixType(
                    $mA,
                    [[$product[0][0], $product[0][1]],
                     [$product[1][0], $product[1][1]]
                     ]
                    );
        }

        return $this->createCorrectMatrixType($mA, $product);
    }
}
