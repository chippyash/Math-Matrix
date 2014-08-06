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

use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Math\Matrix\Exceptions\ComputationException;
use chippyash\Math\Matrix\Attribute\IsNonsingular;

/**
 * Assert matrix is non singular
 */
Trait AssertMatrixIsNonSingular
{

    /**
     * Check that matrix is non singular
     * i.e. det(A) != 0
     *
     * @param \chippyash\Math\Matrix\NumericMatrix $matrix
     *
     * @param string $msg Optional message
     *
     * @return Fluent Interface
     *
     * @throws ComputationException
     */
    protected function assertMatrixIsNonSingular(NumericMatrix $matrix , $msg = 'Matrix is non singular')
    {
        $attr = new IsNonsingular();
        if ($attr->is($matrix)) {
            return $this;
        }

        throw new ComputationException($msg, 110);
    }
}
