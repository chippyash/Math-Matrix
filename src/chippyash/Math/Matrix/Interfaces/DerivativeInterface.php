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
use chippyash\Matrix\Interfaces\InvokableInterface;

/**
 * Interface for a Matrix derivative
 *
 * Derivatives return a single value as a reult of their derivation, usually
 * expected to be numeric
 *
 * @codeCoverageIgnore
 */
interface DerivativeInterface extends InvokableInterface
{
    /**
     * Compute derivative for a matrix
     *
     * @param RationalMatrix $mA
     * @param mixed $extra Additional input required for derivative
     * @return numeric
     * @codeCoverageIgnore
     */
    public function derive(RationalMatrix $mA, $extra = null);

}
