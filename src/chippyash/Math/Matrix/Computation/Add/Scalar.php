<?php
/*
 * Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */

namespace chippyash\Math\Matrix\Computation\Add;

use chippyash\Math\Matrix\Computation\AbstractComputation;
use chippyash\Math\Matrix\RationalMatrix as MMatrix;
use chippyash\Matrix\Traits\AssertMatrixIsComplete;
use chippyash\Math\Matrix\Traits\AssertMatrixIsRational;
use chippyash\Matrix\Traits\ConvertNumberToRational;

/**
 * Add a scalar to every item in the operand matrix
 * @link http://www.php.net//manual/en/function.is-scalar.php
 */
class Scalar extends AbstractComputation
{
    use AssertMatrixIsComplete;
    use AssertMatrixIsRational;
    use ConvertNumberToRational;

    /**
     * Add single scalar value to each member of the matrix and return result
     * Boolean values are converted to 0 (false) and 1 (true).  Use the logical
     * computations if required.
     *
     * @param Matrix $mA First matrix to act on - required
     * @param scalar $extra value to add
     *
     * @return Matrix
     *
     * @throws chippyash/Matrix/Exceptions/ComputationException
     * @todo express product in terms of a FunctionMatrix
     */
    public function compute(MMatrix $mA, $extra = null)
    {
        $this->assertMatrixIsComplete($mA);

        if ($mA->is('empty')) {
            return new MMatrix([]);
        }

        $this->assertMatrixIsRational($mA, 'Matrix mA is not rational');

        $scalar = $this->convertNumberToRational($extra);

        $data = $mA->toArray();
        $m = $mA->rows();
        $n = $mA->columns();
        for ($row = 0; $row < $m; $row++) {
            for ($col = 0; $col < $n; $col++) {
                $data[$row][$col] = $data[$row][$col]->add($scalar);
            }
        }

        return new MMatrix($data);
    }

}
