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

use Chippyash\Type\Number\Rational\RationalTypeFactory;
use Chippyash\Type\Number\Complex\ComplexTypeFactory;
use Chippyash\Type\Interfaces\NumericTypeInterface;
use Chippyash\Matrix\Exceptions\MatrixException;

/**
 * Convert if possible a supplied argument to a rational
 */
Trait ConvertNumberToRational
{
    /**
     * Convert if possible a supplied argument to a rational
     *
     * @param int|float|string|NumericTypeInterface $value
     *
     * @return \Chippyash\Math\Matrix\RationalNumber
     *
     * @throws \Chippyash\Matrix\Exceptions\MatrixException
     * @throws \Exception
     */
    protected function convertNumberToRational($value)
    {
        if($value instanceof NumericTypeInterface) {
            return $value->asRational();
        }

        switch(gettype($value)) {
            case 'integer':
                return RationalTypeFactory::create($value, 1);
            case 'double':
                return RationalTypeFactory::fromFloat($value);
            case 'string':
                try {
                    return RationalTypeFactory::fromString($value);
                } catch (\Exception $e) {
                    try {
                        return ComplexTypeFactory::fromString($value)->asRational();
                    } catch (\Exception $ex) {
                        throw new MatrixException('The string representation of the number is invalid for a rational');
                    }
                }
            case 'NULL':
                return RationalTypeFactory::create(0, 1);
            case 'boolean':
                return RationalTypeFactory::create($value ? 1 : 0, 1);
            default:
                throw new MatrixException('Rational expects int, float, string, Rational or NumericTypeInterface ');
        }
    }
}
