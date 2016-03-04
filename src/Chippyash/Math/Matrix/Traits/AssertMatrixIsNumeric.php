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

use Chippyash\Matrix\Matrix;
use Chippyash\Math\Matrix\Exceptions\ComputationException;
use Chippyash\Math\Matrix\NumericMatrix;

/**
 * Assert matrix is a numeric one
 */
Trait AssertMatrixIsNumeric
{
    /**
     * Check that matrix is rational
     *
     * @param \Chippyash\Matrix\Matrix $matrix
     * @param string $msg Optional message
     * @return Fluent Interface
     *
     * @throws ComputationException
     */
    protected function assertMatrixIsNumeric(Matrix $matrix , $msg = 'Matrix is not numeric')
    {
        if (!$matrix instanceof NumericMatrix) {
            throw new ComputationException($msg, 111);
        }

        return $this;
    }
}
