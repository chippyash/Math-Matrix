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

use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Math\Matrix\Computation\AbstractComputation;
use chippyash\Math\Matrix\Traits\CreateCorrectMatrixType;
use chippyash\Math\Matrix\Traits\CreateCorrectScalarType;
use chippyash\Math\Type\Calculator;

/**
 * Multiply every item in the operand matrix by a scalar value
 * @link http://www.php.net//manual/en/function.is-scalar.php
 */
class Scalar extends AbstractComputation
{
    use CreateCorrectMatrixType;
    use CreateCorrectScalarType;

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
                $data[$row][$col] = $calc->mul($data[$row][$col], $scalar);
            }
        }

        return $this->createCorrectMatrixType($mA, $data);
    }

}
