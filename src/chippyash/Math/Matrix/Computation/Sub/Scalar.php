<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */

namespace chippyash\Math\Matrix\Computation\Sub;

use chippyash\Math\Matrix\Computation\AbstractComputation;
use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Math\Matrix\Traits\CreateCorrectMatrixType;
use chippyash\Math\Matrix\Traits\CreateCorrectScalarType;
use chippyash\Math\Type\Calculator;

/**
 * Subtract a scalar from every item in the operand matrix
 * @link http://www.php.net//manual/en/function.is-scalar.php
 */
class Scalar extends AbstractComputation
{
    use CreateCorrectMatrixType;
    use CreateCorrectScalarType;

    /**
     * Subtract a single scalar value from each member of the matrix and return result
     * Boolean values are converted to 0 (false) and 1 (true).  Use the logical
     * computations if required.
     * String values do a string replace for the scalar, replacing occurences of
     * if with ''
     *
     * @param NumericMatrix $mA First matrix to act on - required
     * @param scalar $extra value to subtract
     *
     * @return Matrix
     *
     * @throws chippyash/Matrix/Exceptions/ComputationException
     */
    public function compute(NumericMatrix $mA, $extra = null)
    {
        if ($mA->is('empty')) {
            return $this->createCorrectMatrixType($mA);
        }

        $scalar = $this->createCorrectScalarType($mA, $extra);

        $data = $mA->toArray();
        $lx = $mA->columns();
        $ly = $mA->rows();
        $calc = new Calculator();
        for ($row = 0; $row < $ly; $row++) {
            for ($col = 0; $col < $lx; $col++) {
                $data[$row][$col] = $calc->sub($data[$row][$col], $scalar);
            }
        }

        return $this->createCorrectMatrixType($mA, $data);
    }

}
