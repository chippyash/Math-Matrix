<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */

namespace chippyash\Math\Matrix\Computation\Mul;

use chippyash\Math\Matrix\Computation\AbstractComputation;
use chippyash\Math\Matrix\RationalMatrix as MMatrix;
use chippyash\Matrix\Traits\AssertMatrixIsComplete;
use chippyash\Math\Matrix\Traits\AssertMatrixIsRational;
use chippyash\Matrix\Traits\AssertParameterIsScalar;
use chippyash\Matrix\Traits\ConvertNumberToRational;

/**
 * Multiply every item in the operand matrix by a scalar value
 * @link http://www.php.net//manual/en/function.is-scalar.php
 */
class Scalar extends AbstractComputation
{
    use AssertMatrixIsRational;
    use AssertMatrixIsComplete;
    use AssertParameterIsScalar;
    use ConvertNumberToRational;

    /**
     * Multiply each member of the matrix by single scalar value and return result
     * Boolean values are converted to 0 (false) and 1 (true).  Use the logical
     * computations if required.
     * String values must conform to the requirements of a rational string number
     * i.e. '2/3', else an exception will be thrown
     *
     * @param Matrix $mA First matrix to act on - required
     * @param scalar $extra value to add
     *
     * @return Matrix
     *
     * @throws chippyash/Matrix/Exceptions/ComputationException
     */
    public function compute(MMatrix $mA, $extra = null)
    {
        if ($mA->is('empty')) {
            return new MMatrix([]);
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
                $data[$row][$col] = $data[$row][$col]->multiplyBy($scalar);
            }
        }

        return new MMatrix($data);
    }

}
