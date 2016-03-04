<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */
namespace Chippyash\Math\Matrix\Attribute;

use Chippyash\Matrix\Interfaces\AttributeInterface;
use Chippyash\Matrix\Matrix;
use Chippyash\Math\Matrix\Traits\AssertMatrixIsNumeric;
use Chippyash\Matrix\Traits\AssertMatrixIsSquare;
use Chippyash\Math\Matrix\Derivative\Determinant;

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
     * @param NumericMatrix $mA
     * @return boolean
     * @throws Chippyash\Matrix\Exceptions\ComputationException;
     */
    public function is(Matrix $mA)
    {
        $this->assertMatrixIsNumeric($mA)
             ->assertMatrixIsSquare($mA);

        $fDet = new Determinant();
        if ($fDet($mA)->get() == 0) {
            return false;
        }

        return true;
    }
}
