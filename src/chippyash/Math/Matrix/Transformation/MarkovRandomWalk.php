<?php
/*
 * Math-Matrix library
 *
 * @author Ashley Kitson <akitson@zf4.biz>
 * @copyright Ashley Kitson, UK, 2014
 * @licence GPL V3 or later : http://www.gnu.org/licenses/gpl.html
 * @link http://en.wikipedia.org/wiki/Matrix_(mathematics)
 */
namespace chippyash\Math\Matrix\Transformation;

use chippyash\Math\Matrix\Exceptions\MathMatrixException;
use chippyash\Matrix\Transformation\AbstractTransformation;
use chippyash\Matrix\Matrix;
use chippyash\Math\Matrix\Exceptions\ComputationException;
use chippyash\Math\Matrix\Traits\AssertMatrixIsNumeric;
use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Math\Type\Calculator;
use chippyash\Math\Type\Comparator;
use chippyash\Type\Number\IntType;
use chippyash\Type\TypeFactory;

/**
 * Perform a random walk through a Markov Chain, returning a Row Vector of the nodes
 * visited
 */
class MarkovRandomWalk extends AbstractTransformation
{

    use AssertMatrixIsNumeric;

    /**
     * Walk a Markov Chain
     *
     * @param Matrix $mA
     * @param null $extra [IntType $start, IntType $target, IntType $limit = 100]
     *
     * @return NumericMatrix
     *
     * @throws ComputationException
     */
    protected function doTransform(Matrix $mA, $extra = null)
    {
        $this->assertMatrixIsNumeric($mA);

        if (!array_key_exists('start', $extra)) {
            throw new MathMatrixException('Supply start row');
        }
        if (!$extra['start'] instanceof IntType) {
            throw new MathMatrixException('Start parameter must be IntType');
        }
        if (!array_key_exists('target', $extra)) {
            throw new MathMatrixException('Supply target row');
        }
        if (!$extra['target'] instanceof IntType) {
            throw new MathMatrixException('Target parameter must be IntType');
        }
        if (!array_key_exists('limit', $extra)) {
            $extra['limit'] = TypeFactory::createInt(100);
        }
        if (!$extra['limit'] instanceof IntType) {
            throw new MathMatrixException('Limit parameter must be IntType');
        }

        return $this->walk($mA, $extra['start'], $extra['target'], $extra['limit']);
    }

    /**
     * @param NumericMatrix $mA
     * @param IntType $start
     * @param IntType $target
     * @param IntType $limit
     * @return NumericMatrix
     */
    protected function walk(NumericMatrix $mA, IntType $start, IntType $target, IntType $limit)
    {
        $zero = new IntType(0);
        $one = new IntType(1);
        $calc = new Calculator();
        $comp = new Comparator();
        $lim = $calc->sub($limit, $one);

        $walk = [$start];

        while ($comp->neq($start, $target) && $comp->neq($lim, $zero)) {
            $start = $mA('MarkovWeightedRandom', $start);
            $walk[] = $start;
            $lim = $calc->sub($lim, $one);
        }

        return new NumericMatrix($walk);
    }
}
