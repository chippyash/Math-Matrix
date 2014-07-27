<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */
namespace chippyash\Math\Matrix\Attribute;

use chippyash\Matrix\Interfaces\AttributeInterface;
use chippyash\Matrix\Matrix;
use chippyash\Matrix\Traits\AssertMatrixIsNumeric;
use chippyash\Matrix\Traits\AssertMatrixIsSquare;
use chippyash\Math\Matrix\Derivative\Determinant;

/**
 * Is the matrix nonsingular?
 * @link http://mathworld.wolfram.com/SingularMatrix.html
 */
class IsNonsingular implements AttributeInterface
{
    use AssertMatrixIsNumeric;
    use AssertMatrixIsSquare;

    /**
     * Does the matrix have this attribute
     * A nonsingular matrix is a square matrix whose determinant != 0
     *
     * @param Matrix $mA
     * @return boolean
     * @throws chippyash\Matrix\Exceptions\ComputationException;
     */
    public function is(Matrix $mA)
    {
        $this->assertMatrixIsNumeric($mA)
             ->assertMatrixIsSquare($mA);

        $fDet = new Determinant();
        if ($fDet($mA) == 0) {
            return false;
        }

        return true;
    }
}
