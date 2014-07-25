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
 * Is matrix an identity matrix?
 */
class IsIdentity implements AttributeInterface
{
    /**
     * Does the matrix have this attribute
     * @link http://en.wikipedia.org/wiki/Identity_matrix
     *
     * @param RationalMatrix $mA
     * @return boolean
     */
    public function is(RationalMatrix $mA)
    {
        if (!$mA->is('square')) {
            return false;
        }
        $rows = $mA->rows();
        $data = $mA->toArray();
        for ($r = 0; $r < $rows; $r++) {
            for ($c = 0; $c < $rows; $c++) {
                //enforce integer content
                if (!is_int($data[$r][$c])) {
                    return false;
                }
                //diagonal == 1
                if (($r == $c) && $data[$r][$c] !== 1) {
                    return false;
                //non-diagonal == 0
                } elseif (($r != $c) && $data[$r][$c] !== 0) {
                    return false;
                }
            }
        }

        return true;
    }
}
