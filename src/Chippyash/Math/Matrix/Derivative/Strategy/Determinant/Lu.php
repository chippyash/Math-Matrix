<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */
namespace Chippyash\Math\Matrix\Derivative\Strategy\Determinant;

use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Math\Matrix\Interfaces\DeterminantStrategyInterface;
use Chippyash\Math\Matrix\Decomposition\Lu as dLu;

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
     * @param \Chippyash\Matrix\NumericMatrix $mA
    *
     * @return \Chippyash\Type\Interfaces\NumericTypeInterface
     */
    public function determinant(NumericMatrix $mA)
    {
        $Lu = new dLu();
        $det = $Lu->decompose($mA)->Det;

        return $det;
    }
}
