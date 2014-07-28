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

use chippyash\Type\Number\Rational\RationalType;
use chippyash\Type\Number\Rational\RationalTypeFactory;
use chippyash\Type\Number\NumericTypeInterface;
use chippyash\Type\Number\IntType;
use chippyash\Matrix\Exceptions\MatrixException;

/**
 * Convert if possible a supplied argument to a rational
 */
Trait ConvertNumberToRational
{
    /**
     * Convert if possible a supplied argument to a rational
     *
     * @param int|float|string|NumericTypeInterface $numerator
     *
     * @return chippyash\Math\Matrix\RationalNumber
     *
     * @throws chippyash\Matrix\Exceptions\MatrixException
     */
    protected function convertNumberToRational($value)
    {
        switch(gettype($value)) {
            case 'integer':
                return new RationalType(new IntType($value), new IntType(1));
            case 'double':
                return RationalTypeFactory::fromFloat($value);
            case 'string':
                try {
                    return RationalTypeFactory::fromString($value);
                } catch (\Exception $e) {
                    throw new MatrixException('The string representation of the number is invalid for a rational');
                }
            case 'object':
                if ($value instanceof RationalType) {
                    return $value;
                } elseif($value instanceof NumericTypeInterface) {
                    return RationalTypeFactory::create($value);
                } else {
                    throw new MatrixException('Rational expects int, float, string, Rational or NumericTypeInterface value');
                }
            case 'NULL':
                return new RationalType(new IntType(0));
            case 'boolean':
                return new RationalType(new IntType($value ? 1 : 0));
            default:
                throw new MatrixException('Rational expects int, float, string, Rational or NumericTypeInterface ');
        }
    }
}
