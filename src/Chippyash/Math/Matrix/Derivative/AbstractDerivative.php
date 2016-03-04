<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */
namespace Chippyash\Math\Matrix\Derivative;

use Chippyash\Math\Matrix\Interfaces\DerivativeInterface;
use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Math\Matrix\Exceptions\ComputationException;
use Chippyash\Matrix\Traits\Debug;

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
     * @param Chippyash\Math\Matrix\NumericMatrix $ma
     * @param mixed $extra
     *
     * @return numeric
     */
    abstract public function derive(NumericMatrix $mA, $extra = null);

    /**
     * Proxy to derive()
     * Allows object to be called as function
     *
     * @param NumericMatrix $mA
     * @param mixed $extra Additional input required for derivative
     *
     * @return numeric
     *
     * @throws Chippyash/Matrix/Exceptions/ComputationException
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
