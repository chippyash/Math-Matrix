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

use chippyash\Math\Matrix\RationalMatrix;

/**
 * Matrix construction using a function
 */
class FunctionMatrix extends RationalMatrix
{

    /**
     * Construct a complete matrix whose entries are a result of a function
     *
     * The function must accept two parameters
     *  e.g. $function($row, $col) {return $row - $col;}
     *
     * $row and $col are 1 based
     *
     * @param callable $function
     * @param int $rows Number of required rows
     * @param int $cols Number or required columns
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(callable $function, $rows, $cols)
    {
        if (!is_int($rows) || $rows < 1) {
            throw new \InvalidArgumentException('$rows must be int >= 1');
        }
        if (!is_int($cols) || $cols < 1) {
            throw new \InvalidArgumentException('$cols must be int >= 1');
        }

        $source = array();
        for ($r = 0; $r < $rows; $r++) {
            for ($c = 0; $c < $cols; $c++) {
                $source[$r][$c] = $function($r + 1, $c + 1);
            }
        }

        parent::__construct($source);
    }

}
