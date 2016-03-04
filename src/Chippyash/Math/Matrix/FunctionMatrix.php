<?php

/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */

namespace Chippyash\Math\Matrix;

use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Type\Number\IntType;

/**
 * Matrix construction using a function
 */
class FunctionMatrix extends NumericMatrix
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
     * @param IntType $rows Number of required rows
     * @param IntType $cols Number or required columns
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(callable $function, IntType $rows, IntType $cols)
    {
        if ($rows() < 1) {
            throw new \InvalidArgumentException('$rows must be >= 1');
        }
        if ($cols() < 1) {
            throw new \InvalidArgumentException('$cols must be >= 1');
        }

        $source = array();
        $rc = $rows();
        $cc = $cols();
        for ($r = 0; $r < $rc; $r++) {
            for ($c = 0; $c < $cc; $c++) {
                $source[$r][$c] = $function($r + 1, $c + 1);
            }
        }

        parent::__construct($source);
    }

}
