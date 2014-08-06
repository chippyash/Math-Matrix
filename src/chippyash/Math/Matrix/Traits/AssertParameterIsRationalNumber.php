<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */
namespace chippyash\Math\Matrix\Traits;

use chippyash\Type\Number\Rational\RationalNumber;
use chippyash\Math\Matrix\Exceptions\ComputationException;

/**
 * Assert parameter is scalar
 */
Trait AssertParameterIsRationalNumber
{
    /**
     * Run test to ensure parameter is a rational number
     *
     * @param mixed $value
     * @param string $msg Optional message
     *
     * @return Fluent Interface
     *
     * @throws ComputationException
     */
    protected function assertParameterIsRationalNumber($param, $msg = 'Parameter is not a rational number!')
    {
        if (!$param instanceof RationalNumber) {
            throw new ComputationException($msg, 106);
        }

        return $this;
    }
}
