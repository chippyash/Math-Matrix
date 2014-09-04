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
use chippyash\Type\Interfaces\NumericTypeInterface;

/**
 * Convert if possible a supplied argument to strong numeric
 */
Trait ConvertNumberToNumeric
{
    /**
     * Convert if possible a supplied argument to a strong numeric type
     *
     * @param int|float|string|NumericTypeInterface $numerator
     * @return chippyash\Type\Interfaces\NumericTypeInterface
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
                    if (is_numeric($value)) {
                        $value = floatval($value);
                    }
                    return TypeFactory::createRational($value);
                } catch (\Exception $e) {
                    try {
                        return TypeFactory::createComplex($value);
                    } catch (\Exception $ex) {
                        throw new MatrixException("The string representation of the number ('{$value}') is invalid for a complex");
                    }
                }
            case 'object':
                if ($value instanceof NumericTypeInterface) {
                    return $value;
                } else {
                    throw new MatrixException('NumberToNumeric expects int, float, string or Rational value');
                }
            case 'NULL':
                return TypeFactory::createInt(0);
            case 'boolean':
                return TypeFactory::createInt(($value ? 1 : 0));
            default:
                throw new MatrixException('NumberToNumeric expects int, float, string or Rational ');
        }
    }
}
