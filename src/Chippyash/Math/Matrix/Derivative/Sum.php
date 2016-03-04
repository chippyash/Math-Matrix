<?php
/**
 * Math-Matrix
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2016, UK
 * @license GPL V3+ See LICENSE.md
 */

namespace Chippyash\Math\Matrix\Derivative;


use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Math\Type\Calculator;
use Chippyash\Type\Number\IntType;

/**
 * Sum Derivative
 * Sum all vertices and return single NumericType value
 */
class Sum extends AbstractDerivative
{

    /**
     * @inheritDoc
     */
    public function derive(NumericMatrix $mA, $extra = null)
    {
        if ($mA->is('empty')) {
            return new IntType(0);
        }
        if ($mA->is('singleitem')) {
            return $mA->get(1,1);
        }

        $calc = new Calculator();

        return array_reduce($mA->toArray(), function ($c1, $row) use ($calc) {
            return array_reduce($row, function($carry, $item) use ($calc) {
                return $calc->add($item, $carry);
            },
                $c1
            );
        },
            new IntType(0)
        );
    }
}