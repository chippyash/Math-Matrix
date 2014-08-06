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

use chippyash\Type\Number\Complex\ComplexType;
use chippyash\Type\Number\Complex\ComplexTypeFactory;
use chippyash\Type\Number\NumericTypeInterface;
use chippyash\Type\Number\IntType;
use chippyash\Matrix\Exceptions\MatrixException;

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
     * @return chippyash\Math\Matrix\RationalNumber
     *
     * @throws chippyash\Matrix\Exceptions\MatrixException
     */
    protected function convertNumberToComplex($value)
    {
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
            case 'object':
                if($value instanceof NumericTypeInterface) {
                    return $value->asComplex();
                } else {
                    throw new MatrixException('Complex expects int, float, string, or NumericTypeInterface value');
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
