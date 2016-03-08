<?php
/**
 * Math-Matrix
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2016, UK
 * @license GPL V3+ See LICENSE.md
 */

namespace Chippyash\Math\Matrix\Computation\Mul;


use Chippyash\Math\Matrix\Computation\AbstractEntryWiseComputation;
use Chippyash\Math\Type\Calculator;
use Chippyash\Type\Interfaces\NumericTypeInterface;

class Entrywise extends AbstractEntryWiseComputation
{

    /**
     * @inheritDoc
     */
    protected function doCompute(NumericTypeInterface $a, NumericTypeInterface $b, Calculator $calc)
    {
        return $calc->mul($a, $b);
    }
}