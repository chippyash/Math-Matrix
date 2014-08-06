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
            if ($originalMatrix instanceof RationalMatrix) {
                return $scalar->asRational();
            }
            if ($originalMatrix instanceof ComplexMatrix) {
                return $scalar->asComplex();
            }
            return $scalar;
        }
        if ($originalMatrix instanceof ComplexMatrix) {
            if (is_numeric($scalar)) {
                return ComplexTypeFactory::create($scalar, 0);
            }
            if (is_string($scalar)) {
                try {
                    return RationalTypeFactory::create($scalar)->asComplex();
                } catch (\Exception $e) {
                    //do nothing
                }
            }
            if (is_bool($scalar)) {
                return ComplexTypeFactory::create(($scalar ? 1 : 0), 0);
            }
            return ComplexTypeFactory::create($scalar);
        }
        if ($originalMatrix instanceof RationalMatrix) {
            if (is_bool($scalar)) {
                $scalar = ($scalar ? 1 : 0);
            }
            return RationalTypeFactory::create($scalar);
        }

        //handling for NumericMatrix
        if (is_int($scalar)) {
            return TypeFactory::createInt($scalar);
        } elseif (is_float($scalar)) {
            return TypeFactory::createRational($scalar);
        } elseif(is_bool($scalar)) {
            return TypeFactory::createInt(($scalar ? 1 : 0));
        } elseif(is_string($scalar)) {
            try {
                return TypeFactory::createRational($scalar);
            } catch(\InvalidArgumentException $e) {
                try {
                    return ComplexTypeFactory::create($scalar);
                } catch (\InvalidArgumentException $e) {
                    //do nothing
                }
            }
        }

        throw new ComputationException('Scalar parameter is not a supported type for numeric matrices: ' . getType($scalar));
    }
}
