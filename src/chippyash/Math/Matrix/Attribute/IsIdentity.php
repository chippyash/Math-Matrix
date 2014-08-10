<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */
namespace chippyash\Math\Matrix\Attribute;

use chippyash\Matrix\Interfaces\AttributeInterface;
use chippyash\Matrix\Matrix;
use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Type\Number\NumericTypeInterface;
use chippyash\Type\Number\Complex\ComplexType;

/**
 * Is matrix an identity matrix?
 * Can only be applied to numeric and complex matrices
 */
class IsIdentity implements AttributeInterface
{

    /**
     * Does the matrix have this attribute
     * @link http://en.wikipedia.org/wiki/Identity_matrix
     *
     * @param Matrix $mA
     * @return boolean
     */
    public function is(Matrix $mA)
    {
        if (!$mA instanceof NumericMatrix) {
            return false;
        }
        $rows = $mA->rows();
        $cols = $mA->columns();
        $data = $mA->toArray();
        for ($r = 0; $r < $rows; $r++) {
            for ($c = 0; $c < $cols; $c++) {
                $item = $this->checkItem($data[$r][$c]);
                //diagonal == 1
                if (($r == $c) && $item != 1) {
                    return false;
                //non-diagonal == 0
                } elseif (($r != $c) && $item != 0) {
                    return false;
                }
            }
        }

        return true;
    }

    protected function checkItem($item)
    {
        if ($item instanceof ComplexType) {
            if ($item() == '1+0i') {
                return 1;
            } elseif ($item->isZero()) {
                return 0;
            } else {
                return -1;
            }
        }
        //any other numericType will caste down as required
        return $item();
    }
}
