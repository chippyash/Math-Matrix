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

use chippyash\Matrix\Exceptions\MatrixException;
use chippyash\Type\TypeFactory;
use chippyash\Type\Number\NumericTypeInterface;
use chippyash\Type\Number\Complex\ComplexType;

/**
 * Convert if possible a supplied argument to strong numeric
 */
Trait ConvertNumberToNumeric
{
    /**
     * Convert if possible a supplied argument to a strong numeric type
     * This does not include complex types
     * 
     * @param int|float|string $numerator
     * @return chippyash\Type\Number\NumericTypeInterface
     * @throws UndefinedComputationException
     */
    protected function convertNumberToNumeric($value)
    {
        switch(gettype($value)) {
            case 'integer':
                return TypeFactory::createInt($value);
            case 'double':
                return TypeFactory::createRational($value);
            case 'string':
                try {
                    return TypeFactory::createRational($value);
                } catch (\Exception $e) {
                    throw new MatrixException("The string representation of the number ('{$value}') is invalid for a rational");
                }
            case 'object':
                if ($value instanceof NumericTypeInterface
                        && !$value instanceof ComplexType) {
                    return $value;
                } else {
                    throw new MatrixException('NumberToNumeric expects int, float, string or Rational value');
                }
            case 'NULL':
                return TypeFactory::createInt($value);
            case 'boolean':
                return TypeFactory::createInt(($value ? 1 : 0));
            default:
                throw new MatrixException('NumberToNumeric expects int, float, string or Rational ');
        }
    }
}
