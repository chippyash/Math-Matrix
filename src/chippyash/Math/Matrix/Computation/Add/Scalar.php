<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */

namespace chippyash\Math\Matrix\Computation\Add;

use chippyash\Math\Matrix\Computation\AbstractComputation;
use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Math\Matrix\Traits\CreateCorrectMatrixType;
use chippyash\Math\Matrix\Traits\CreateCorrectScalarType;
use chippyash\Math\Type\Calculator;

/**
 * Add a scalar to every item in the operand matrix
 * @link http://www.php.net//manual/en/function.is-scalar.php
 */
class Scalar extends AbstractComputation
{
    use CreateCorrectMatrixType;
    use CreateCorrectScalarType;

    /**
     * Add single scalar value to each member of the matrix and return result
     *
     * @param NumericMatrix $mA First matrix to act on - required
     * @param scalar $extra value to add
     *
     * @return NumericMatrix|RationalMatrix|ComplexMatrix
     *
     * @throws chippyash/Matrix/Exceptions/ComputationException
     * @todo express product in terms of a FunctionMatrix
     */
    public function compute(NumericMatrix $mA, $extra = null)
    {
        if ($mA->is('empty')) {
            return $this->createCorrectMatrixType($mA);
        }

        $scalar = $this->createCorrectScalarType($mA, $extra);

        $data = $mA->toArray();
        $m = $mA->rows();
        $n = $mA->columns();
        $calc = new Calculator();
        for ($row = 0; $row < $m; $row++) {
            for ($col = 0; $col < $n; $col++) {
                $data[$row][$col] = $calc->add($data[$row][$col], $scalar);
            }
        }

        return $this->createCorrectMatrixType($mA, $data);
    }

}
