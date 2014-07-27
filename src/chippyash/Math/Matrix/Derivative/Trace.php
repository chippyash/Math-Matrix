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

use chippyash\Matrix\Derivative\AbstractDerivative;
use chippyash\Math\Matrix\RationalMatrix;
use chippyash\Math\Matrix\Exceptions\UndefinedComputationException;

/**
 * Find the Trace of a square matrix tr(M)
 */
class Trace extends AbstractDerivative
{

    /**
     * Find tr(M)
     *
     * @param RationalMatrix $mA
     * @param mixed $extra
     * @return numeric
     *
     * @throws chippyash/Math/Matrix/Exceptions/UndefinedComputationException
     */
    public function derive(RationalMatrix $mA, $extra = null)
    {
        if ($mA->is('empty')) {
            throw new UndefinedComputationException('No trace for empty matrix');
        }

        if ($mA->is('singleitem')) {
            return $mA->get(1,1);
        }

        if (!$mA->is('square')) {
            throw new UndefinedComputationException('No trace for non-square matrix');
        }

        $tr = 0;
        $size = $mA->rows();
        $data = $mA->toArray();
        for ($x = 0; $x < $size; $x++) {
            $tr += $data[$x][$x];
        }

        return $tr;
    }

}
