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
use Chippyash\Math\Matrix\RationalMatrix;
/**
 * Assert matrix composed of rational numbers
 */
Trait AssertMatrixIsRational
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
    protected function assertMatrixIsRational(Matrix $matrix , $msg = 'Matrix is not rational')
    {
        if (!$matrix instanceof RationalMatrix) {
            throw new ComputationException($msg, 111);
        }

        return $this;
    }
}
