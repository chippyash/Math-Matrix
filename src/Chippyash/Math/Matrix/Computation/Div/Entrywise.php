<?php
/**
 * Math-Matrix
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2016, UK
 * @license GPL V3+ See LICENSE.md
 */

namespace Chippyash\Math\Matrix\Computation\Div;


use Chippyash\Math\Matrix\Computation\AbstractEntryWiseComputation;
use Chippyash\Math\Type\Calculator;
use Chippyash\Math\Type\Comparator;
use Chippyash\Type\Interfaces\NumericTypeInterface;
use Chippyash\Type\Number\IntType;

class Entrywise extends AbstractEntryWiseComputation
{
    /**
     * @var Comparator
     */
    private $comp;

    /**
     * @inheritDoc
     */
    protected function doCompute(NumericTypeInterface $a, NumericTypeInterface $b, Calculator $calc)
    {
        if ($this->getComparator()->compare($b, new IntType(0)) === 0) {
            return null;
        }
        return $calc->div($a, $b);
    }

    /**
     * @return Comparator
     */
    private function getComparator()
    {
        if (is_null($this->comp)) {
            $this->comp = new Comparator();
        }

        return $this->comp;
    }
}