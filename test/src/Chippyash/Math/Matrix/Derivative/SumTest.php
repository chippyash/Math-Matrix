<?php
/**
 * Math-Matrix
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2016, UK
 * @license GPL V3+ See LICENSE.md
 */

namespace Chippyash\Test\Math\Matrix\Derivative;

use Chippyash\Math\Matrix\Derivative\Sum;
use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Type\Interfaces\NumericTypeInterface;
use Chippyash\Type\Number\Rational\RationalTypeFactory;
use Chippyash\Type\RequiredType;
use Chippyash\Type\TypeFactory;

class SumTest extends \PHPUnit_Framework_TestCase
{
    /**
     * System Under Test
     * @var
     */
    protected $sut;

    protected function setUp()
    {
        RequiredType::getInstance()->set(RequiredType::TYPE_NATIVE);
        $this->sut = new Sum();
    }


    public function testSutHasDerivativeInterface()
    {
        $this->assertInstanceOf(
            'Chippyash\Math\Matrix\Interfaces\DerivativeInterface',
            $this->sut);
    }

    public function testSummingAZeroMatrixReturnsIntZero()
    {
        $test = (new NumericMatrix([]))->derive($this->sut);
        $this->assertInstanceOf('Chippyash\Type\Number\IntType', $test);
        $this->assertEquals(0, $test->get());
    }

    public function testSummingASingleItemMatrixReturnsTheSingleItem()
    {
        $value = TypeFactory::createInt(53);
        $test = (new NumericMatrix([$value]))->derive($this->sut);
        $this->assertInstanceOf('Chippyash\Type\Number\IntType', $test);
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
            [[[1,2,3]], TypeFactory::createInt(6)],
            [[[1],[2],[3]], TypeFactory::createInt(6)],
            [[[1,2,3],[1,2,3]], TypeFactory::createInt(12)],
            [[[1.1,2,3],[1,2.2,3]], RationalTypeFactory::fromFloat(12.3)],
            [[[0.5,0.5]], RationalTypeFactory::create(1)]
        ];
    }
}
