<?php
/**
 * Math-Matrix
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2016, UK
 * @license GPL V3+ See LICENSE.md
 */

namespace Chippyash\Math\Matrix\Derivative;


use Chippyash\Math\Matrix\Exceptions\MathMatrixException;
use Chippyash\Math\Matrix\Exceptions\NotMarkovException;
use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Math\Type\Calculator;
use Chippyash\Math\Type\Comparator;
use Chippyash\Type\Number\IntType;
use Chippyash\Type\TypeFactory;
use Assembler\FFor;
use Monad\FTry;
use Monad\Match;

class MarkovWeightedRandom extends AbstractDerivative
{
    /**
     * @var Comparator;
     */
    protected $comp;
    /**
     * @var Calculator
     */
    protected $calc;
    /**
     * @var IntType
     */
    protected $zero;

    /**
     * Find the derivative
     *
     * @param NumericMatrix $mA
     * @param IntType $extra The current row to find the next weighted random row from
     *
     * @throws MathMatrixException
     * @throws NotMarkovException
     *
     * @return IntType
     */
    public function derive(NumericMatrix $mA, $extra = null)
    {
        if (!$mA->is('Markov')) {
            throw new NotMarkovException();
        }

        if (!$extra instanceof IntType) {
            throw new MathMatrixException('The extra parameter is not an IntType');
        }

        $this->comp = new Comparator();
        $this->calc = new Calculator();
        $this->zero = TypeFactory::createInt(0);

        return $this->nextWeightedRandom($mA, $extra);
    }

    /**
     * Returns random key from input array by its weight
     * Array must be specified in [key => weight, ...] form
     *
     * @param NumericMatrix $mA Matrix to process
     * @param IntType $current Chain row to get next pick from
     *
     * @return IntType Random key to matrix row
     *
     * @throws MathMatrixException
     */
    protected function nextWeightedRandom(NumericMatrix $mA, IntType $current)
    {
        $array = FFor::create(['row' => $current, 'mA' => $mA])
            ->slice(function($row, $mA) {$t = $mA('Rowslice', [$row()])->toArray(); return array_pop($t);})
            ->array(function($slice) {
                return array_filter(
                    $slice,
                    function ($item) {
                        return $this->comp->neq($this->zero, $item);
                    }
                );
            })
            ->fyield('array');

        if (count($array) <= 1) {
            return TypeFactory::createInt(key($array));
        }
        $sum = (new NumericMatrix([$array]))->derive(new Sum())->asIntType();
        if ($this->comp->lte($sum, $this->zero)) {
            throw new MathMatrixException('Negative or all-zero weights not allowed');
        }

        return $this->getNextRow($sum, $array);;
    }

    /**
     * @param $sum
     * @param $rowArray
     *
     * @return mixed
     *
     * @throws MathMatrixException
     */
    protected function getNextRow($sum, $rowArray)
    {
        return FFor::create(['sum' => $sum, 'rowArray' => $rowArray])
            ->targetWeight(function($sum) {
                return TypeFactory::createInt(mt_rand(1, $sum()));
            })
            ->nextRow(function($targetWeight, $rowArray) {
                foreach ($rowArray as $key => $weight) {
                    if ($this->comp->lt($weight, $this->zero)) {
                        throw new MathMatrixException('Negative weights not allowed');
                    }
                    $targetWeight = $this->calc->sub($targetWeight, $weight);
                    if ($this->comp->lte($targetWeight, $this->zero)) {
                        return TypeFactory::createInt($key + 1);
                    }
                }
            })
            ->fyield('nextRow');
    }
}