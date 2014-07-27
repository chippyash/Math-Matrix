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
use chippyash\Math\Matrix\RationalMatrix;
use chippyash\Matrix\Traits\AssertMatrixIsComplete;
use chippyash\Math\Matrix\Traits\AssertMatrixIsRational;
use chippyash\Matrix\Traits\AssertParameterIsMatrix;
use chippyash\Matrix\Traits\AssertMatrixColumnsAreEqual;
use chippyash\Matrix\Traits\AssertMatrixRowsAreEqual;
use chippyash\Math\Matrix\ZeroMatrix as ZMatrix;

/**
 * Add two matrices together
 * @link http://www.php.net//manual/en/function.is-scalar.php
 */
class Matrix extends AbstractComputation
{
    use AssertMatrixIsComplete;
    use AssertMatrixIsRational;
    use AssertParameterIsMatrix;
    use AssertMatrixRowsAreEqual;
    use AssertMatrixColumnsAreEqual;

    /**
     * Add two matrices together
     * dot product
     *
     * @param RationalMatrix $mA First matrix operand - required
     * @param RationalMatrix $extra Second Matrix operand - required
     *
     * @return RationalMatrix
     */
    public function compute(RationalMatrix $mA, $extra = null)
    {
        $this->assertMatrixIsRational($extra, 'Parameter is not a rational matrix');

        if ($mA->is('empty') || $extra->is('empty')) {
            return new RationalMatrix([]);
        }

        $this->assertMatrixColumnsAreEqual($mA, $extra)
             ->assertMatrixRowsAreEqual($mA, $extra);

        $mZ = new ZMatrix($mA->rows(), $mA->columns());
        $data = $mZ->toArray();

        $dA = $mA->toArray();
        $dB = $extra->toArray();

        $cols = $mA->columns();
        $rows = $mA->rows();

        for ($row=0; $row<$rows; $row++) {
            for ($col=0; $col<$cols; $col++) {
                //this is where having operator overide makes sense :-(
                $data[$row][$col] = $dA[$row][$col]->add($dB[$row][$col]);
            }
        }

        return new RationalMatrix($data);
    }

}
