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

use Chippyash\Math\Type\Comparator;
use Chippyash\Type\Interfaces\NumericTypeInterface;
use Chippyash\Type\TypeFactory;

/**
 * Construct a matrix with all entries set to 0/1
 * @deprecated use SpecialMatrix('zeros',...) instead
 */
class ZeroMatrix extends FunctionMatrix
{
    /**
     * Construct a Matrix with all entries set to IntType(0)
     *
     * @param NumericTypeInterface $rows Number of required rows
     * @param NumericTypeInterface $cols Number of required columns
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(NumericTypeInterface $rows, NumericTypeInterface $cols)
    {
        $comp = new Comparator();
        $one = TypeFactory::createInt(1);
        if ($comp->lt($rows, $one)) {
            throw new \InvalidArgumentException('$rows must be >= 1');
        }
        if ($comp->lt($cols, $one)) {
            throw new \InvalidArgumentException('$cols must be >= 1');
        }

        $f = function($row, $col) {
            return TypeFactory::createInt(0);
        };

        parent::__construct($f, $rows, $cols);
    }
}
