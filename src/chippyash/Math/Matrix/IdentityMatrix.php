<?php
/*
 * Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */
namespace chippyash\Math\Matrix;

use chippyash\Math\Matrix\FunctionMatrix;

/**
 * Construct an identity matrix
 */
class IdentityMatrix extends FunctionMatrix
{
    /**
     * Construct a square matrix whose entries on the diagonal ==  1 or 1/1
     * All other entries == 0 or 0/1
     *
     * @param int $size Number of required rows and columns
     * @param boolean $rationalise Turn numeric values into Rational numbers
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($size, $rationalise = true)
    {
        if (!is_int($size) || $size < 1) {
            throw new \InvalidArgumentException('$size must be int >= 1');
        }

        $f = function($row, $col) {
            return ($row == $col ? 1 : 0);
        };

        parent::__construct($f, $size, $size, $rationalise);
    }
}
