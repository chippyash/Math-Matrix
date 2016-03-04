<?php

/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */

namespace Chippyash\Math\Matrix\Transformation\Strategy\Invert;

use Chippyash\Matrix\Transformation\Cofactor;
use Chippyash\Matrix\Transformation\Transpose;
use Chippyash\Math\Matrix\Computation\Div\Scalar;
use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Math\Matrix\Derivative\Determinant as Det;
use Chippyash\Math\Matrix\Interfaces\InversionStrategyInterface;
use Chippyash\Math\Matrix\Traits\CreateCorrectMatrixType;

/**
 * Determinant strategy for matrix inversion
 *
 */
class Determinant implements InversionStrategyInterface
{
    use CreateCorrectMatrixType;

    /**
     * Compute inverse using determinants method
     * We are expecting a non singular, square matrix (complete, n=m, n>1)
     *
     * @param \Chippyash\Matrix\Matrix $mA
     * @return Matrix
     */
    public function invert(NumericMatrix $mA)
    {
        $rows = $mA->rows();
        $cols = $mA->columns();
        $work = array();
        $fDet = new Det();
        $fCof = new Cofactor();
        for ($row = 0; $row < $rows; $row++) {
            for ($col = 0; $col < $cols; $col++) {
                $t = $fDet($fCof($mA,[$row + 1, $col + 1]));
                if (fmod($row + $col, 2) == 0) {
                    $work[$row][$col] = $t;
                } else {
                    $work[$row][$col] = $t->negate();
                }
                $r = $row + 1;
                $c = $col + 1;
            }
        }
        $fTr = new Transpose();
        $fDiv = new Scalar();

        return $fTr($fDiv($this->createCorrectMatrixType($mA, $work), $fDet($mA)));
    }

}
