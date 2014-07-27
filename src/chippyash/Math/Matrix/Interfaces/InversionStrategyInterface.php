<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */
namespace chippyash\Math\Matrix\Interfaces;

use chippyash\Math\Matrix\NumericMatrix;

/**
 * Interface for inversion strategies
 * @codeCoverageIgnore
 */
interface InversionStrategyInterface
{
    /**
     * Invert the matrix
     *
     * @param NumericMatrix $mA
     * @return NumericMatrix
     * @codeCoverageIgnore
     */
    public function invert(NumericMatrix $mA);
}
