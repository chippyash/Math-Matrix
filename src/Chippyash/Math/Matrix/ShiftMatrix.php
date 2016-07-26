<?php
/**
 * Math-Matrix
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2016, UK
 * @license BSD 3 Clause See LICENSE.md
 */

namespace Chippyash\Math\Matrix;

use Chippyash\Matrix\Transformation\Shift;
use Chippyash\Matrix\Transformation\Transpose;
use Chippyash\Type\Number\IntType;
use Chippyash\Type\String\StringType;
use Chippyash\Type\Number\Rational\RationalTypeFactory;
use Chippyash\Type\Number\Complex\ComplexTypeFactory;
use Chippyash\Type\TypeFactory;

/**
 * Class ShiftMatrix
 * @see https://en.wikipedia.org/wiki/Shift_matrix
 */
class ShiftMatrix extends NumericMatrix
{

    const SM_TYPE_UPPER = 'upper';
    const SM_TYPE_LOWER = 'lower';

    /**
     * ShiftMatrix constructor.
     * 
     * @param IntType $size Number of required rows and columns
     * @param StringType $shiftType SM_TYPE_UPPER|SM_TYPE_LOWER
     * @param IntType|null $identityType Type of identity entries: default == IdentityType::IDM_TYPE_INT
     * 
     */
    public function __construct(IntType $size, StringType $shiftType, IntType $identityType = null)
    {
        $mA = new NumericMatrix(new IdentityMatrix($size, $identityType));
        $idt = (is_null($identityType) ? IdentityMatrix::IDM_TYPE_INT : $identityType()); 

        if ($idt == IdentityMatrix::IDM_TYPE_RATIONAL) {
            $new = RationalTypeFactory::create(0);
        } elseif ($idt == IdentityMatrix::IDM_TYPE_COMPLEX) {
            $new = ComplexTypeFactory::create(
                RationalTypeFactory::create(0),
                RationalTypeFactory::create(0, 1));
        } else {
            $new = TypeFactory::createInt(0);
        }

        $fT = new Transpose();
        $fS = new Shift();

        if ($shiftType() == self::SM_TYPE_UPPER) {
            $mB = $fS($mA, [1, $new]);
        }

        if ($shiftType() == self::SM_TYPE_LOWER) {
            $mB = $fS($mA, [-1, $new]);
        }

        parent::__construct($mB);
    }

}