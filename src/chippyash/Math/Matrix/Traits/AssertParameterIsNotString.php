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

use chippyash\Math\Matrix\RationalMatrix;
use chippyash\Math\Matrix\Exceptions\ComputationException;

/**
 * Assert parameter is not a string
 */
Trait AssertParameterIsNotString
{
    /**
     * Run test to ensure parameter is not a string
     *
     * @param mixed $value
     * @param string $msg Optional message
     *
     * @return Fluent Interface
     *
     * @throws ComputationException
     */
    protected function assertParameterIsNotString($param, $msg = 'Parameter is a string')
    {
        if (is_string($param) && !is_numeric($param)) {
            throw new ComputationException($msg, 107);
        }

        return $this;
    }
}
