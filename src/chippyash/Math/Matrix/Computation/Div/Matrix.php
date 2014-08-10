<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */

namespace chippyash\Math\Matrix\Computation\Div;

use chippyash\Math\Matrix\Computation\AbstractComputation;
use chippyash\Math\Matrix\Computation\Mul\Matrix as MM;
use chippyash\Math\Matrix\Transformation\Invert;
use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Math\Matrix\Traits\CreateCorrectMatrixType;
use chippyash\Matrix\Traits\AssertParameterIsMatrix;
use chippyash\Matrix\Traits\AssertMatrixColumnsAreEqual;
use chippyash\Matrix\Traits\AssertMatrixRowsAreEqual;

/**
 * Divide one matrix by another
 * This is the same as multiplying by the inverse of the divisor
 * i.e. Where a, b are numeric matrices and i = inverse(b)
 * then a/b = a * i
 *
 * @link http://www.php.net//manual/en/function.is-scalar.php
 */
class Matrix extends AbstractComputation
{
    use AssertParameterIsMatrix;
    use AssertMatrixRowsAreEqual;
    use AssertMatrixColumnsAreEqual;
    use CreateCorrectMatrixType;

    /**
     * Divide a mtix by another
     *
     * @param NumericMatrix $mA First matrix operand - required
     * @param NumericMatrix $extra Second Matrix operand - required
     *
     * @return NumericMatrix
     */
    public function compute(NumericMatrix $mA, $extra = null)
    {
        $this->assertParameterIsMatrix($extra, 'Parameter is not a matrix');

        if ($mA->is('empty')) {
            $mA = $this->createCorrectMatrixType($mA, [1]);
        }
        if ($extra->is('empty')) {
            $extra = $this->createCorrectMatrixType($extra, [1]);
        }

        $this->assertMatrixColumnsAreEqual($mA, $extra)
             ->assertMatrixRowsAreEqual($mA, $extra);

        $fI = new Invert();
        $mI = $fI->transform($extra);

        $mul = new MM();
        return $mul($mA, $mI);
    }

}
