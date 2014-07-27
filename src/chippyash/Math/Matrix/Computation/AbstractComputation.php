<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */
namespace chippyash\Math\Matrix\Computation;

use chippyash\Math\Matrix\Interfaces\ComputatationInterface;
use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Math\Matrix\Exceptions\ComputationException;
use chippyash\Matrix\Traits\Debug;

/**
 * Base abstract for computations
 *
 * Has invokable interface
 */
abstract class AbstractComputation implements ComputatationInterface
{
    use Debug;

    /**
     * Carry out a computation and return the result
     * MUST be overriden
     *
     * @param NumericMatrix $mA First matrix to act on - required
     * @param mixed $extra NumericMatrix or other parameter required by the computation
     *
     * @return NumericMatrix
     *
     * @throws \BadMethodCallException
     */
    abstract public function compute(NumericMatrix $mA, $extra = null);

    /**
     * Proxy to compute()
     * Allows object to be called as function
     *
     * @param NumericMatrix $mA First matrix to act on - required
     * @param mixed $extra NumericMatrix or other parameter required by the computation
     *
     * @return NumericMatrix
     *
     * @throws chippyash/Matrix/Exceptions/ComputationException
     */
    public function __invoke()
    {
        $numArgs = func_num_args();
        if ($numArgs == 1) {
            return $this->compute(func_get_arg(0));
        } elseif($numArgs == 2) {
            return $this->compute(func_get_arg(0), func_get_arg(1));
        } else {
            throw new ComputationException('Invoke method expects 0<n<3 arguments');
        }
    }
}
