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
use chippyash\Math\Matrix\Traits\AssertMatrixIsNumeric;
use chippyash\Matrix\Traits\AssertMatrixRowsAreEqual;
use chippyash\Matrix\Traits\AssertMatrixColumnsAreEqual;
use chippyash\Matrix\Traits\AssertParameterIsMatrix;
use chippyash\Math\Type\Calculator;

/**
 * Subtract matrices
 * @link http://www.php.net//manual/en/function.is-scalar.php
 */
class Matrix extends AbstractComputation
{
    use CreateCorrectMatrixType;
    use AssertMatrixIsNumeric;
    use AssertMatrixRowsAreEqual;
    use AssertMatrixColumnsAreEqual;
    use AssertParameterIsMatrix;

    /**
     * Subtract one matrix from another
     * Boolean values are converted to 0 (false) and 1 (true).  Use the logical
     * computations if required.
     * String values do a string replace for the scalar, replacing occurences of
     * if with ''
     *
     * @param MMatrix $mA First matrix operand - required
     * @param MMatrix $extra Second Matrix operand - required
     *
     * @return MMatrix
     *
     * @throws chippyash/Matrix/Exceptions/ComputationException
     */
    public function compute(NumericMatrix $mA, $extra = null)
    {
        $this->assertParameterIsMatrix($extra, 'Parameter is not a matrix')
                ->assertMatrixIsNumeric($extra, 'Parameter is not numeric matrix');

        if ($mA->is('empty') || $extra->is('empty')) {
            return $this->createCorrectMatrixType($mA, [[]]);
        }

        $this->assertMatrixRowsAreEqual($mA, $extra)
             ->assertMatrixColumnsAreEqual($mA, $extra);

        $data = array();
        $dA = $mA->toArray();
        $dB = $extra->toArray();
        $cols = $mA->columns();
        $rows = $mA->rows();
        $calc = new Calculator();
        for ($row=0; $row<$rows; $row++) {
            for ($col=0; $col<$cols; $col++) {
                $data[$row][$col] = $calc->sub($dA[$row][$col], $dB[$row][$col]);
            }
        }

        return $this->createCorrectMatrixType($mA, $data);
    }

}
