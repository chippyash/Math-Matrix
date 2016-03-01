<?php
/**
 * Math-Matrix
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2016, UK
 * @license GPL V3+ See LICENSE.md
 */

namespace chippyash\Test\Math\Matrix\Derivative;

use chippyash\Math\Matrix\Derivative\MarkovWeightedRandom;
use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Type\Number\IntType;
use chippyash\Type\RequiredType;

class MarkovWeightedRandomTest extends \PHPUnit_Framework_TestCase
{
    /**
     * System Under Test
     * @var MarkovWeightedRandom
     */
    protected $sut;

    protected function setUp()
    {
        RequiredType::getInstance()->set(RequiredType::TYPE_NATIVE);
        $this->sut = new MarkovWeightedRandom();
    }

    public function testIfOnlyOneChoiceIsAvailableForANextLinkItWillBeReturned()
    {
        $chain = new NumericMatrix([[0,1],[1,0]]);
        $this->assertEquals(1, $this->sut->derive($chain, new IntType(1))->get());
    }

    /**
     * @expectedException Exception
     */
    public function testAZeroSumOfAChainRowWillThrowAnException()
    {
        $chain = new NumericMatrix([[0,0],[1,0]]);
        $this->sut->derive($chain, new IntType(1))->get();
    }

    /**
     * @expectedException Exception
     */
    public function testANonIntegerSumOfAChainRowWillThrowAnException()
    {
        $chain = new NumericMatrix([[0,0.1,0.2],[1,0,0],[0,1,0]]);
        $this->sut->derive($chain, new IntType(1))->get();
    }

    /**
     * @expectedException Exception
     */
    public function testUsingNegativeValuesWillThrowAnException()
    {
        $chain = new NumericMatrix([[-1,2,0],[1,0,0],[0,1,0]]);
        $this->sut->derive($chain, new IntType(1))->get();
    }

    public function testYouCanPickTheNextLinkInAChain()
    {
        $chain = new NumericMatrix(
            [
                [2, 0, 6, 2],
                [0, 0, 6, 4],
                [8, 1, 1, 0],
                [0, 5, 0, 5]
            ]
        );
        $res = [2=>0,3=>0];
        for ($x = 0; $x < 100; $x++) {
            $test = $this->sut->derive($chain, new IntType(2));
            $res[$test() - 1] ++;
        }

        $this->assertEquals(100, array_sum($res));
    }

}
