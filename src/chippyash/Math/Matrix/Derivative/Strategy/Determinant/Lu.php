<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */
namespace chippyash\Math\Matrix\Derivative\Strategy\Determinant;

use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Math\Matrix\Interfaces\DeterminantStrategyInterface;
use chippyash\Math\Matrix\Decomposition\Lu as dLu;

/**
 * LU strategy for matrix determinant
 *
 */
class Lu implements DeterminantStrategyInterface
{
   /**
     * Compute determinant using LU method
     * $mA must be
     * - square
     * This is not checked here - that is done in the determinant derivative class
     *
     * @param \chippyash\Matrix\NumericMatrix $mA
    *
     * @return \chippyash\Type\Number\NumericTypeInterface
     */
    public function determinant(NumericMatrix $mA)
    {
        $Lu = new dLu();
        $det = $Lu->decompose($mA)->Det;

        return $det;
    }
}
