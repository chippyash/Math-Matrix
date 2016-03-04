<?php
/**
 * Math-Matrix
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2016, UK
 * @license GPL V3+ See LICENSE.md
 */

namespace Chippyash\Test\Math\Matrix\Transformation;

use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Math\Matrix\Transformation\MarkovRandomWalk;
use Chippyash\Type\TypeFactory;
use Chippyash\Type\RequiredType;

class MarkovRandomWalkTest extends \PHPUnit_Framework_TestCase
{
    /**
     * System Under Test
     * @var MarkovRandomWalk
     */
    protected $sut;

    protected function setUp()
    {
        RequiredType::getInstance()->set(RequiredType::TYPE_NATIVE);
        $this->sut = new MarkovRandomWalk();
    }

    /**
     * @expectedException \Chippyash\Math\Matrix\Exceptions\MathMatrixException
     * @expectedExceptionMessage Supply start row
     */
    public function testYouMustSupplyAStartRowParameter()
    {
        $chain = new NumericMatrix($this->chainData());
        $this->sut->transform($chain, array());
    }

    /**
     * @expectedException \Chippyash\Math\Matrix\Exceptions\MathMatrixException
     * @expectedExceptionMessage Start parameter must be IntType
     */
    public function testTheStartParameterMustBeAnInttype()
    {
        $chain = new NumericMatrix($this->chainData());
        $this->sut->transform($chain, array('start'=> TypeFactory::createFloat(2)));
    }

    /**
     * @expectedException \Chippyash\Math\Matrix\Exceptions\MathMatrixException
     * @expectedExceptionMessage Target parameter must be IntType
     */
    public function testTheTargetParameterMustBeAnInttype()
    {
        $chain = new NumericMatrix($this->chainData());
        $this->sut->transform(
            $chain,
            array(
                'start'=> TypeFactory::createInt(2),
                'target'=>TypeFactory::createFloat(4)
            )
        );
    }

    /**
     * @expectedException \Chippyash\Math\Matrix\Exceptions\MathMatrixException
     * @expectedExceptionMessage Limit parameter must be IntType
     */
    public function testTheLimitParameterIfProvidedMustBeAnInttype()
    {
        $chain = new NumericMatrix($this->chainData());
        $this->sut->transform(
            $chain,
            array(
                'start'=> TypeFactory::createInt(2),
                'target'=>TypeFactory::createInt(4),
                'limit'=>TypeFactory::createFloat(3)
            )
        );
    }

    /**
     * @expectedException \Chippyash\Math\Matrix\Exceptions\MathMatrixException
     * @expectedExceptionMessage Supply target row
     */
    public function testYouMustSupplyATargetRowParameter()
    {
        $chain = new NumericMatrix($this->chainData());
        $this->sut->transform($chain, array('start'=>TypeFactory::createInt(2)));
    }

    public function testYouCanWalkThroughTheChainByWeightedRandomSelectionAndReturnARowVectorOfInttypes()
    {
        $chain = new NumericMatrix($this->chainData());
        $res = $this->sut->transform(
            $chain,
            array(
                'start'=>TypeFactory::createInt(2),
                'target'=>TypeFactory::createInt(4)
            )
        );

        $this->assertInstanceOf('Chippyash\Math\Matrix\NumericMatrix', $res);
        $this->assertTrue($res->is('Rowvector'));
        $linkArr = $res->toArray();
        foreach(array_pop($linkArr) as $rowNum) {
            $this->assertInstanceOf('Chippyash\Type\Number\IntType', $rowNum);
        }
    }

    public function testYouCanPlaceALimitOnTheWalk()
    {
        $chain = new NumericMatrix($this->chainData());
        $res = $this->sut->transform(
            $chain,
            array(
                'start'=>TypeFactory::createInt(4),
                'target'=>TypeFactory::createInt(2),
                'limit'=>TypeFactory::createInt(2)
            )
        );

        $this->assertEquals(2, $res->columns());
        $this->assertEquals(1, $res->rows());

    }

    protected function chainData() {
        return [
            [2, 0, 6, 2],
            [0, 0, 6, 4],
            [8, 1, 1, 0],
            [0, 5, 0, 5],
        ];
    }
}
