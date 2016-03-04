<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */

namespace Chippyash\Math\Matrix\Computation\Add;

use Chippyash\Math\Matrix\Computation\AbstractComputation;
use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Math\Matrix\Traits\CreateCorrectMatrixType;
use Chippyash\Matrix\Traits\AssertParameterIsMatrix;
use Chippyash\Matrix\Traits\AssertMatrixColumnsAreEqual;
use Chippyash\Matrix\Traits\AssertMatrixRowsAreEqual;
use Chippyash\Math\Matrix\ZeroMatrix as ZMatrix;
use Chippyash\Type\Number\IntType;
use Chippyash\Math\Type\Calculator;

/**
 * Add two matrices together
 * @link http://www.php.net//manual/en/function.is-scalar.php
 */
class Matrix extends AbstractComputation
{
    use AssertParameterIsMatrix;
    use AssertMatrixRowsAreEqual;
    use AssertMatrixColumnsAreEqual;
    use CreateCorrectMatrixType;

    /**
     * Add two matrices together
     * dot product
     *
     * @param NumericMatrix $mA First matrix operand - required
     * @param NumericMatrix $extra Second Matrix operand - required
     *
     * @return NumericMatrix
     */
    public function compute(NumericMatrix $mA, $extra = null)
    {
        $this->assertParameterIsMatrix($extra, 'Parameter is not a matrix');

        if ($mA->is('empty') || $extra->is('empty')) {
            return $this->createCorrectMatrixType($mA, []);
        }

        $this->assertMatrixColumnsAreEqual($mA, $extra)
             ->assertMatrixRowsAreEqual($mA, $extra);

        $mZ = new ZMatrix(new IntType($mA->rows()), new IntType($mA->columns()));
        $data = $mZ->toArray();

        $dA = $mA->toArray();
        $dB = $extra->toArray();

        $cols = $mA->columns();
        $rows = $mA->rows();
        $calc = new Calculator();

        for ($row=0; $row<$rows; $row++) {
            for ($col=0; $col<$cols; $col++) {
                //this is where having operator overide makes sense :-(
                $data[$row][$col] = $calc->add($dA[$row][$col], $dB[$row][$col]);
            }
        }

        return $this->createCorrectMatrixType($mA, $data);
    }

}
