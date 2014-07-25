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
use chippyash\Matrix\Traits\AssertParameterIsMatrix;
use chippyash\Matrix\Traits\AssertMatrixRowsAreEqual;
use chippyash\Matrix\Traits\AssertMatrixColumnsAreEqual;
use chippyash\Matrix\Traits\AssertParameterIsScalar;

/**
 * Subtract matrices
 * @link http://www.php.net//manual/en/function.is-scalar.php
 */
class Matrix extends AbstractComputation
{
    use AssertMatrixIsComplete;
    use AssertParameterIsMatrix;
    use AssertMatrixRowsAreEqual;
    use AssertMatrixColumnsAreEqual;
    use AssertParameterIsScalar;

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
    public function compute(MMatrix $mA, $extra = null)
    {
        $this->assertMatrixIsComplete($mA)
             ->assertParameterIsMatrix($extra)
             ->assertMatrixIsComplete($extra);

        if ($mA->is('empty') || $extra->is('empty')) {
            return new MMatrix(array());
        }

        $this->assertMatrixRowsAreEqual($mA, $extra)
             ->assertMatrixColumnsAreEqual($mA, $extra);

        $data = array();
        $dA = $mA->toArray();
        $dB = $extra->toArray();
        $cols = $mA->columns();
        $rows = $mA->rows();
        for ($row=0; $row<$rows; $row++) {
            for ($col=0; $col<$cols; $col++) {
                $this->assertParameterIsScalar($dA[$row][$col])
                     ->assertParameterIsScalar($dB[$row][$col]);

                if (is_string($dA[$row][$col]) || is_string($dB[$row][$col])) {
                    $data[$row][$col] = str_replace((string) $dB[$row][$col],'',(string) $dA[$row][$col]);
                } elseif (is_bool($dA[$row][$col]) || is_bool($dB[$row][$col])) {
                    $data[$row][$col] = ((boolean) $dA[$row][$col] ? 1 : 0) - ((boolean) $dB[$row][$col] ? 1 : 0);
                } else {
                    $data[$row][$col] = $dA[$row][$col] - $dB[$row][$col];
                }
            }
        }

        return new MMatrix($data);
    }

}
