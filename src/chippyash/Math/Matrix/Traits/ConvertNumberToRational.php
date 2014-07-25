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

use chippyash\Math\Matrix\RationalNumber;
use chippyash\Matrix\Exceptions\MatrixException;

/**
 * Convert if possible a supplied argument to a rational
 */
Trait ConvertNumberToRational
{
    /**
     * Convert if possible a supplied argument to a rational
     *
     * @param int|float|string $numerator
     * @return chippyash\Math\Matrix\RationalNumber
     * @throws UndefinedComputationException
     */
    protected function convertNumberToRational($value)
    {
        switch(gettype($value)) {
            case 'integer':
                return new RationalNumber($value);
            case 'double':
                return RationalNumber::fromReal($value);
            case 'string':
                try {
                    return RationalNumber::fromString($value);
                } catch (\Exception $e) {
                    throw new MatrixException('The string representation of the number is invalid for a rational');
                }
            case 'object':
                if ($value instanceof RationalNumber) {
                    return $value;
                } else {
                    throw new MatrixException('Rational expects int, float, string or Rational value');
                }
            case 'NULL':
                return new RationalNumber(0);
            case 'boolean':
                return new RationalNumber($value ? 1 : 0);
            default:
                throw new MatrixException('Rational expects int, float, string or Rational ');
        }
    }
}
