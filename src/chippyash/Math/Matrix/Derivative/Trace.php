<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */

namespace chippyash\Math\Matrix\Derivative;

<<<<<<< HEAD
=======
use chippyash\Math\Matrix\Derivative\AbstractDerivative;
>>>>>>> 48473dea2ad6395867fa2efd81822b45248dd8c9
use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Math\Matrix\Exceptions\UndefinedComputationException;
use chippyash\Math\Type\Calculator;
use chippyash\Type\Number\FloatType;
use chippyash\Matrix\Traits\AssertMatrixIsNotEmpty;
use chippyash\Matrix\Traits\AssertMatrixIsSquare;

/**
 * Find the Trace of a square matrix tr(M)
 */
class Trace extends AbstractDerivative
{
    use AssertMatrixIsNotEmpty;
    use AssertMatrixIsSquare;

    /**
     * Find tr(M)
     *
     * @param NumericMatrix $mA
     * @param mixed $extra
     * @return numeric
     *
     * @throws chippyash/Math/Matrix/Exceptions/UndefinedComputationException
     */
    public function derive(NumericMatrix $mA, $extra = null)
    {
        if ($mA->is('singleitem')) {
            return $mA->get(1,1);
        }

        $this->assertMatrixIsNotEmpty($mA, 'No trace for empty matrix')
                ->assertMatrixIsSquare($mA, 'No trace for non-square matrix');

        $tr = new FloatType(0);
        $size = $mA->rows();
        $data = $mA->toArray();
        $calc = new Calculator();
        for ($x = 0; $x < $size; $x++) {
            $tr = $calc->add($tr, $data[$x][$x]);
        }

        return $tr;
    }

}
