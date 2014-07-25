<?php
/*
 * Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */
namespace chippyash\Math\Matrix\Interfaces;

use chippyash\Math\Matrix\RationalMatrix;

/**
 * Interface for inversion strategies
 * @codeCoverageIgnore
 */
interface InversionStrategyInterface
{
    /**
     * Invert the matrix
     *
     * @param RationalMatrix $mA
     * @return RationalMatrix
     * @codeCoverageIgnore
     */
    public function invert(RationalMatrix $mA);
}
