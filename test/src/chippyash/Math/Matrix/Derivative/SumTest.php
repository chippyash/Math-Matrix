<?php
/**
 * Math-Matrix
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2016, UK
 * @license GPL V3+ See LICENSE.md
 */

namespace chippyash\Test\Math\Matrix\Derivative;

use chippyash\Math\Matrix\Derivative\Sum;
use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Type\Interfaces\NumericTypeInterface;
use chippyash\Type\Number\IntType;
use chippyash\Type\Number\Rational\RationalTypeFactory;

class SumTest extends \PHPUnit_Framework_TestCase
{
    /**
     * System Under Test
     * @var
     */
    protected $sut;

    protected function setUp()
    {
        $this->sut = new Sum();
    }

    public function testSummingAZeroMatrixReturnsIntZero()
    {
        $test = (new NumericMatrix([]))->derive($this->sut);
        $this->assertInstanceOf('chippyash\Type\Number\IntType', $test);
        $this->assertEquals(0, $test->get());
    }

    public function testSummingASingleItemMatrixReturnsTheSingleItem()
    {
        $value = new IntType(53);
        $test = (new NumericMatrix([$value]))->derive($this->sut);
        $this->assertInstanceOf('chippyash\Type\Number\IntType', $test);
        $this->assertEquals(53, $test->get());
    }

    /**
     * @dataProvider mixedMatrices
     * @param array $source
     * @param NumericTypeInterface $result
     */
    public function testSummingAnNPlusMatrixReturnsTheSumOfTheVertices(array $source, NumericTypeInterface $result)
    {
        $test = (new NumericMatrix($source))->derive($this->sut);
        $this->assertInstanceOf(get_class($result), $test);
        $this->assertEquals($result->get(), $test->get());
    }

    public function mixedMatrices()
    {
        return [
            [[[1,2,3]], new IntType(6)],
            [[[1],[2],[3]], new IntType(6)],
            [[[1,2,3],[1,2,3]], new IntType(12)],
            [[[1.1,2,3],[1,2.2,3]], RationalTypeFactory::fromFloat(12.3)],
        ];
    }
}
