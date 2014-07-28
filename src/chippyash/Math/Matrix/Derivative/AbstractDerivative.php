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

use chippyash\Math\Matrix\Interfaces\DerivativeInterface;
use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Math\Matrix\Exceptions\ComputationException;
use chippyash\Matrix\Traits\Debug;

/**
 * Base abstract for derivative
 *
 * Has invokable interface
 */
abstract class AbstractDerivative implements DerivativeInterface
{
    use Debug;

    /**
     * @see DerivativeInterface::derive
     * @abstract
     *
     * @param chippyash\Math\Matrix\NumericMatrix $ma
     * @raparm mixed $extra
     */
    abstract public function derive(NumericMatrix $mA, $extra = null);

    /**
     * Proxy to derive()
     * Allows object to be called as function
     *
     * @param RationalMatrix $mA
     * @param mixed $extra Additional input required for derivative
     *
     * @return numeric
     *
     * @throws chippyash/Matrix/Exceptions/ComputationException
     */
    public function __invoke()
    {
        $numArgs = func_num_args();
        if ($numArgs == 1) {
            return $this->derive(func_get_arg(0));
        } elseif($numArgs == 2) {
            return $this->derive(func_get_arg(0), func_get_arg(1));
        } else {
            throw new ComputationException('Invoke method expects 0<n<3 arguments');
        }
    }
}
