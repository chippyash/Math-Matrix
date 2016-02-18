<?php
/**
 * Math-Matrix
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2016, UK
 * @license GPL V3+ See LICENSE.md
 */

namespace chippyash\Math\Matrix\Derivative;


use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Math\Type\Calculator;
use chippyash\Type\Number\IntType;

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

        return array_reduce($mA->toArray(), function ($carry, $row) use ($calc) {
            return array_reduce($row, function($carry, $item) use ($calc) {
                return $calc->add($item, $carry);
            },
                $carry
            );
        },
            new IntType(0)
        );
    }
}