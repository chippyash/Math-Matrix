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
use Chippyash\Type\Number\IntType;
use Chippyash\Type\String\StringType;
use Chippyash\Type\TypeFactory;
use Chippyash\Math\Matrix\Special\Identity;

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
        $mA = (new Identity())->create([$size()]);
        $new = TypeFactory::createInt(0);

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