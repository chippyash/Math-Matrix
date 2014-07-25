<?php
/*
 * Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */
namespace chippyash\Math\Matrix\Attribute;

use chippyash\Matrix\Interfaces\AttributeInterface;
use chippyash\Math\Matrix\Derivative\Determinant;
use chippyash\Math\Matrix\RationalMatrix;
use chippyash\Matrix\Traits\AssertMatrixIsSquare;

/**
 * Is the matrix nonsingular?
 * @link http://mathworld.wolfram.com/SingularMatrix.html
 */
class IsNonsingular implements AttributeInterface
{
    use AssertMatrixIsSquare;

    /**
     * Does the matrix have this attribute
     * A nonsingular matrix is a square matrix whose determinant != 0
     *
     * @param RationalMatrix $mA
     * @return boolean
     * @throws chippyash\Matrix\Exceptions\ComputationException;
     */
    public function is(RationalMatrix $mA)
    {

        $this->assertMatrixIsSquare($mA);

        $fDet = new Determinant();
        if ($fDet($mA) == 0) {
            return false;
        }
        //alternate method
//        $data = $mA->toArray();
//        for ($j = 0; $j < $mA->columns(); $j++) {
//            if (!isset($data[$j][$j]) || $data[$j][$j] == 0)
//                return false;
//        }

        return true;
    }
}
