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

use Chippyash\Type\Number\Rational\RationalType;
use Chippyash\Math\Matrix\Exceptions\ComputationException;

/**
 * Assert parameter is a rational number
 */
Trait AssertParameterIsRationalNumber
{
    /**
     * Run test to ensure parameter is a rational number
     *
     * @param mixed $param
     * @param string $msg Optional message
     *
     * @return Fluent Interface
     *
     * @throws ComputationException
     */
    protected function assertParameterIsRationalNumber($param, $msg = 'Parameter is not a rational number!')
    {
        if (!$param instanceof RationalType) {
            throw new ComputationException($msg, 106);
        }

        return $this;
    }
}
