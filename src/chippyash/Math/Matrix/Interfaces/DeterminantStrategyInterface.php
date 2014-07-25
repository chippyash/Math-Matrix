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
 * Interface for a Matrix determinant stratgey
 *
 * @codeCoverageIgnore
 */
interface DeterminantStrategyInterface
{
    /**
     * Compute determinant for a matrix
     *
     * @param RationalMatrix $mA
     * @return numeric
     * @codeCoverageIgnore
     */
    public function determinant(RationalMatrix $mA);

}
