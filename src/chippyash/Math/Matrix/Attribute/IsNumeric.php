<?php
/*
 * Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */
namespace chippyash\Math\Matrix\Attribute;

use chippyash\Matrix\Interfaces\AttributeInterface;
use chippyash\Math\Matrix\RationalMatrix;

/**
 * Is matrix a numeric matrix. i.e. all entries are numeric?
 */
class IsNumeric implements AttributeInterface
{
    /**
     * Does the matrix have this attribute
     *
     * @param RationalMatrix $mA
     * @return boolean
     */
    public function is(RationalMatrix $mA)
    {
        if (!$mA->is('complete')) {
            return false;
        }
        $rows = $mA->rows();
        $cols = $mA->columns();
        $data = $mA->toArray();
        for ($r = 0; $r < $rows; $r++) {
            for ($c = 0; $c < $cols; $c++) {
                if (!is_numeric($data[$r][$c])) {
                    return false;
                }
            }
        }

        return true;
    }
}
