<?php
/*
 * Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */

namespace chippyash\Math\Matrix\Computation\Div;

use chippyash\Math\Matrix\Computation\AbstractComputation;
use chippyash\Math\Matrix\RationalMatrix as MMatrix;
use chippyash\Math\Matrix\Exceptions\ComputationException;
use chippyash\Matrix\Traits\AssertMatrixIsComplete;
use chippyash\Math\Matrix\Traits\AssertMatrixIsRational;
use chippyash\Matrix\Traits\ConvertNumberToRational;

/**
 * Divide every item in the operand matrix by a scalar value
 * @link http://www.php.net//manual/en/function.is-scalar.php
 */
class Scalar extends AbstractComputation
{
    use AssertMatrixIsRational;
    use AssertMatrixIsComplete;
    use ConvertNumberToRational;

    /**
     * Divide each member of the matrix by single scalar value and return result
     * Boolean values are converted to 0 (false) and 1 (true).  Use the logical
     * computations if required.
     * String values (although scalar) cannot be divided so will cause an exception
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
            return new MMatrix([], false, false, null, $mA->isRational());
        }

        $this->assertMatrixIsRational($mA)
             ->assertMatrixIsComplete($mA);

        //convert to rational as we don't know exact format of incoming data
        //will throw an exception if not possible
        $scalar = $this->convertNumberToRational($extra)->reciprocal();
        if ($scalar->getDenominator() === 0) {
            throw new ComputationException('Parameter == zero');
        }

        $data = $mA->toArray();
        $lx = $mA->columns();
        $ly = $mA->rows();
        for ($row = 0; $row < $ly; $row++) {
            for ($col = 0; $col < $lx; $col++) {
                $data[$row][$col] = $data[$row][$col]->multiplyBy($scalar)->reduce();
            }
        }

        return new MMatrix($data);
    }

}
