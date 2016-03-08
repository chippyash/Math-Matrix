<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */

namespace Chippyash\Math\Matrix\Computation\Add;

use Chippyash\Math\Matrix\Computation\AbstractEntryWiseComputation;
use Chippyash\Type\Interfaces\NumericTypeInterface;
use Chippyash\Math\Type\Calculator;

/**
 * Add two matrices together
 */
class Matrix extends AbstractEntryWiseComputation
{
    /**
     * @inheritDoc
     */
    protected function doCompute(NumericTypeInterface $a, NumericTypeInterface $b, Calculator $calc)
    {
        return $calc->add($a, $b);
    }
}
