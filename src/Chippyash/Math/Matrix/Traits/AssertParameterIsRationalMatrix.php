<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */
namespace Chippyash\Math\Matrix\Traits;

use Chippyash\Math\Matrix\RationalMatrix;
use Chippyash\Math\Matrix\Exceptions\ComputationException;

/**
 * Assert parameter is a rational Matrix
 */
Trait AssertParameterIsRationalMatrix
{
    /**
     * Run test to ensure parameter is a Rational Matrix
     *
     * @param mixed $param
     * @param string $msg Optional message
     *
     * @return Fluent Interface
     *
     * @throws ComputationException
     */
    protected function assertParameterIsRationalMatrix($param, $msg = 'Parameter is not a rational matrix')
    {
        if (!$param instanceof RationalMatrix) {
            throw new ComputationException($msg, 103);
        }

        return $this;
    }
}
