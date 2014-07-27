<?php

/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */

namespace chippyash\Math\Matrix\Transformation\Strategy\Invert;

use chippyash\Matrix\Derivative\Determinant as Det;
use chippyash\Matrix\Transformation\Cofactor;
use chippyash\Matrix\Transformation\Transpose;
use chippyash\Matrix\Computation\Div\Scalar;
use chippyash\Math\Matrix\RationalMatrix;
use chippyash\Math\Matrix\Exceptions\ComputationException;
use chippyash\Matrix\Interfaces\InversionStrategyInterface;

/**
 * Determinant strategy for matrix inversion
 *
 */
class Determinant implements InversionStrategyInterface
{
    /**
     * Compute inverse using determinants method
     * We are expecting a non singular, square matrix (complete, n=m, n>1)
     *
     * @param \chippyash\Matrix\Matrix $mA
     * @return Matrix
     * @throws ComputationException
     */
    public function invert(Matrix $mA)
    {
        $rows = $mA->rows();
        $cols = $mA->columns();
        $work = array();
        $fDet = new Det();
        $fCof = new Cofactor();
        try {
            for ($row = 0; $row < $rows; $row++) {
                for ($col = 0; $col < $cols; $col++) {
                    if (fmod($row + $col, 2) == 0) {
                        $work[$row][$col] = $fDet($fCof($mA,
                                        array($row + 1, $col + 1)));
                    } else {
                        $work[$row][$col] = -$fDet($fCof($mA,
                                                array($row + 1, $col + 1)));
                    }
                    $r = $row + 1;
                    $c = $col + 1;
                }
            }
            $fTr = new Transpose();
            $fDiv = new Scalar();

            return $fTr($fDiv(new Matrix($work), $fDet($mA)));
        } catch (ComputationException $e) {
            $msg = str_replace('Computation Error: ', '', $e->getMessage());
            throw new ComputationException('Matrix is not invertible: ' . $msg,
            100, $e);
        }
    }

}
