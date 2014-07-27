<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */
namespace chippyash\Math\Matrix;

use chippyash\Math\Matrix\FunctionMatrix;

/**
 * Construct a matrix with all entries set to 0/1
 */
class ZeroMatrix extends FunctionMatrix
{
    /**
     * Construct a Matrix with all entries set to 0 or 0/1 (Rational)
     *
     * @param int $rows Number of required rows
     * @param int $cols Number of required columns
     * @param boolean $rationalise Turn numeric values into Rational numbers
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($rows, $cols, $rationalise = true)
    {
        if (!is_int($rows) || $rows < 1) {
            throw new \InvalidArgumentException('$rows must be int >= 1');
        }
        if (!is_int($cols) || $cols < 1) {
            throw new \InvalidArgumentException('$rows must be int >= 1');
        }

        $f = function($row, $col) {
            return 0;
        };

        parent::__construct($f, $rows, $cols, $rationalise);
    }
}
