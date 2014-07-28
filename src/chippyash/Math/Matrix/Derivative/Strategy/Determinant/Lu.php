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
use chippyash\Math\Matrix\Transformation\Decomposition\Lu as dLu;
use chippyash\Type\Number\IntType;

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
     * @param \chippyash\Matrix\NumericMatrix $mA
    *
     * @return \chippyash\Type\Number\IntType|chippyash\Type\Number\FloatType
     */
    public function determinant(NumericMatrix $mA)
    {
        $Lu = new dLu();
        var_dump($Lu->transform($mA)->Det);exit;
        $det = $Lu->transform($mA)->Det;
        if ($det() == 0) {
            return new IntType(0);
        }
        if (($det() / round($det())) == 1.0) {
            return new IntType($det);
        }

        return $det; //FloatType
    }
}
