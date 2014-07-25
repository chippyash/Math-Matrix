<?php
/*
 * Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */
namespace chippyash\Math\Matrix\Derivative\Strategy\Determinant;

use chippyash\Math\Matrix\RationalMatrix;
use chippyash\Matrix\Interfaces\DeterminantStrategyInterface;
use chippyash\Matrix\Transformation\Decomposition\Lu as dLu;

/**
 * LU strategy for matrix inversion
 *
 */
class Lu implements DeterminantStrategyInterface
{
    /**
     * Cofactor function
     * @var function
     */
    protected $fCof;

   /**
     * Compute determinant using LU method
     * $mA must be
     * - square
     * This is not checked here - that is done in the determinant derivative class
     *
     * @param \chippyash\Matrix\Matrix $mA
     * @return double|int
     * @throws ComputationException
     */
    public function determinant(Matrix $mA)
    {
        $Lu = new dLu();
        $det = (real) $Lu->transform($mA)->Det;
        if ($det == 0) {
            return 0;
        }
        if (($det / round($det)) == 1.0) {
            return (int) $det;
        }

        return $det; //double
    }
}
