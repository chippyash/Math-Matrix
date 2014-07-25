<?php
/*
 * Matrix library
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
 * Assert parameter is a rational Matrix
 */
Trait AssertParameterIsRationalMatrix
{
    /**
     * Run test to ensure parameter is a Matrix
     *
     * @param mixed $value
     * @param string $msg Optional message
     *
     * @return Fluent Interface
     *
     * @throws ComputationException
     */
    protected function assertParameterIsMatrix($param, $msg = 'Parameter is not a rational matrix')
    {
        if (!$param instanceof RationalMatrix) {
            throw new ComputationException($msg, 103);
        }

        return $this;
    }
}
