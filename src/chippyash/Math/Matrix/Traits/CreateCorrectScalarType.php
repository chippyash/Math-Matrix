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
use chippyash\Math\Matrix\RationalMatrix;
use chippyash\Math\Matrix\ComplexMatrix;
use chippyash\Type\Number\Complex\ComplexTypeFactory;
use chippyash\Type\Number\Rational\RationalTypeFactory;
use chippyash\Type\Number\NumericTypeInterface;
use chippyash\Type\TypeFactory;
use chippyash\Math\Matrix\Exceptions\ComputationException;

/**
 * Create a new scalar based on type of original matrix
 */
Trait CreateCorrectScalarType
{

    /**
     * Create a new scalar based on type of original matrix
     *
     * @param \chippyash\Math\Matrix\NumericMatrix $originalMatrix
     * @param scalar $scalar
     * @return chippyash\Type\Number\NumericTypeInterface
     *
     */
    protected function createCorrectScalarType(NumericMatrix $originalMatrix , $scalar)
    {
        if ($scalar instanceof NumericTypeInterface) {
            return $scalar;
        }
        if (!is_scalar($scalar)) {
            throw new ComputationException('Scalar parameter is not scalar');
        }
        if ($originalMatrix instanceof ComplexMatrix) {
            return ComplexTypeFactory::create($scalar);
        }
        if ($originalMatrix instanceof RationalMatrix) {
            return RationalTypeFactory::create($scalar);
        }
        
        if (is_int($scalar)) {
            return TypeFactory::create('int', $scalar);
        } elseif (is_float($scalar)) {
            return TypeFactory::create('float', $scalar);
        } else {
            throw new ComputationException('Scalar parameter is not a supported type for numeric matrices: ' . getType($scalar));
        }
    }
}
