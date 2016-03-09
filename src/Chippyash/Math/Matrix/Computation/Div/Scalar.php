<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */

namespace Chippyash\Math\Matrix\Computation\Div;

use Chippyash\Math\Matrix\Computation\AbstractComputation;
use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Math\Matrix\Exceptions\ComputationException;
use Chippyash\Math\Matrix\Traits\CreateCorrectMatrixType;
use Chippyash\Math\Matrix\Traits\CreateCorrectScalarType;
use Chippyash\Math\Type\Calculator;
use Chippyash\Type\Number\IntType;
use Chippyash\Type\Number\FloatType;
use Chippyash\Type\Number\Rational\RationalType;

/**
 * Divide every item in the operand matrix by a scalar value
 * @link http://www.php.net//manual/en/function.is-scalar.php
 */
class Scalar extends AbstractComputation
{
    use CreateCorrectMatrixType;
    use CreateCorrectScalarType;

    /**
     * Divide each member of the matrix by single scalar value and return result
     *
     * @param NumericMatrix $mA First matrix to act on - required
     * @param numeric $extra value to add
     *
     * @return Matrix
     *
     * @throws Chippyash/Matrix/Exceptions/ComputationException
     */
    public function compute(NumericMatrix $mA, $extra = null)
    {
        if ($mA->is('empty')) {
            return $this->createCorrectMatrixType($mA);
        }

        $scalar = $this->createCorrectScalarType($mA, $extra);

        if ($this->isZero($scalar)) {
            throw new ComputationException('Divisor == zero');
        }

        $data = $mA->toArray();
        $lx = $mA->columns();
        $ly = $mA->rows();
        $calc = new Calculator();
        for ($row = 0; $row < $ly; $row++) {
            for ($col = 0; $col < $lx; $col++) {
                $data[$row][$col] = $calc->div($data[$row][$col], $scalar);
            }
        }

        return $this->createCorrectMatrixType($mA, $data);
    }

    protected function isZero($number)
    {
        if ($number instanceof IntType || $number instanceof FloatType) {
            return ($number() == 0);
        }
        if ($number instanceof RationalType) {
            return ($number->numerator()->get() == 0);
        }
        //must be complex
        return $number->isZero();
    }
}
