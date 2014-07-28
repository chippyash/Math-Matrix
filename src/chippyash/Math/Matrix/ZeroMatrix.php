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
use chippyash\Type\Number\IntType;
/**
 * Construct a matrix with all entries set to 0/1
 */
class ZeroMatrix extends FunctionMatrix
{
    /**
     * Construct a Matrix with all entries set to IntType(0)
     *
     * @param chippyash\Type\Number\IntType $rows Number of required rows
     * @param chippyash\Type\Number\IntType $cols Number of required columns
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(IntType $rows, IntType $cols)
    {
        if ($rows() < 1) {
            throw new \InvalidArgumentException('$rows must be >= 1');
        }
        if ($cols() < 1) {
            throw new \InvalidArgumentException('$rows must be >= 1');
        }

        $f = function($row, $col) {
            return new IntType(0);
        };

        parent::__construct($f, $rows, $cols);
    }
}
