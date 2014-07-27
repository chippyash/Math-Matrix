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
use chippyash\Matrix\Interfaces\InvokableInterface;

/**
 * Computation interface
 * Computations must never modify the operands to the computation
 * and always return a Matrix as a result.
 *
 * ComputationException based exceptions must be thrown if computation fails
 * for any reason
 *
 * @codeCoverageIgnore
 */
interface ComputatationInterface extends InvokableInterface
{
    /**
     * Carry out a computation and return the result
     *
     * @param NumericMatrix $mA First matrix to act on - required
     * @param mixed $extra NumericMatrix or other parameter required by the computation
     *
     * @return NumericMatrix
     *
     * @throws chippyash/Matrix/Exceptions/ComputationException
     * @codeCoverageIgnore
     */
    public function compute(NumericMatrix $mA, $extra = null);

}
