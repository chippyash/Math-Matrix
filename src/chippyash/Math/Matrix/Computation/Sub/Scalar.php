<?php
/*
 * Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */

namespace chippyash\Math\Matrix\Computation\Sub;

use chippyash\Math\Matrix\Computation\AbstractComputation;
use chippyash\Math\Matrix\RationalMatrix as MMatrix;
use chippyash\Matrix\Traits\AssertMatrixIsComplete;
use chippyash\Math\Matrix\Traits\AssertMatrixIsRational;
use chippyash\Matrix\Traits\AssertParameterIsScalar;
use chippyash\Matrix\Traits\ConvertNumberToRational;

/**
 * Subtract a scalar from every item in the operand matrix
 * @link http://www.php.net//manual/en/function.is-scalar.php
 */
class Scalar extends AbstractComputation
{
    use AssertMatrixIsRational;
    use AssertMatrixIsComplete;
    use AssertParameterIsScalar;
    use ConvertNumberToRational;

    /**
     * Subtract a single scalar value from each member of the matrix and return result
     * Boolean values are converted to 0 (false) and 1 (true).  Use the logical
     * computations if required.
     * String values do a string replace for the scalar, replacing occurences of
     * if with ''
     *
     * @param Matrix $mA First matrix to act on - required
     * @param scalar $extra value to subtract
     *
     * @return Matrix
     *
     * @throws chippyash/Matrix/Exceptions/ComputationException
     */
    public function compute(MMatrix $mA, $extra = null)
    {
        if ($mA->is('empty')) {
            return new MMatrix(array(), false, false, null, $mA->isRational());
        }

        $this->assertMatrixIsComplete($mA, 'Matrix mA is not complete')
             ->assertMatrixIsRational($mA, 'Matrix mA is not rational')
             ->assertParameterIsScalar($extra);

        $scalar = $this->convertNumberToRational($extra);

        $data = $mA->toArray();
        $lx = $mA->columns();
        $ly = $mA->rows();
        for ($row = 0; $row < $ly; $row++) {
            for ($col = 0; $col < $lx; $col++) {
                $data[$row][$col] = $data[$row][$col]->subtract($scalar);
            }
        }

        return new MMatrix($data);
    }

}
