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

use Chippyash\Type\Number\Complex\ComplexTypeFactory;
use Chippyash\Type\Interfaces\NumericTypeInterface;
use Chippyash\Matrix\Exceptions\MatrixException;

/**
 * Convert if possible a supplied argument to a complex
 */
Trait ConvertNumberToComplex
{
    /**
     * Convert if possible a supplied argument to a complex
     *
     * @param int|float|string|NumericTypeInterface $numerator
     *
     * @return Chippyash\Math\Matrix\RationalNumber
     *
     * @throws Chippyash\Matrix\Exceptions\MatrixException
     */
    protected function convertNumberToComplex($value)
    {
        if($value instanceof NumericTypeInterface) {
            return $value->asComplex();
        }

        switch(gettype($value)) {
            case 'integer':
            case 'double':
                return ComplexTypeFactory::create($value, 0);
            case 'string':
                try {
                    return ComplexTypeFactory::fromString($value);
                } catch (\Exception $e) {
                    throw new MatrixException('The string representation of the number is invalid for a complex number');
                }
            case 'NULL':
                return ComplexTypeFactory::create(0, 0);
            case 'boolean':
                return ComplexTypeFactory::create(($value ? 1 : 0), 0);
            default:
                throw new MatrixException('Complex expects int, float, string, or NumericTypeInterface value');
        }
    }
}
