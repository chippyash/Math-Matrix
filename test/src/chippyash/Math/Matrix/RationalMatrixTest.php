<?php

namespace chippyash\Test\Math\Matrix;

use chippyash\Math\Matrix\RationalMatrix;
use chippyash\Type\Number\Rational\RationalTypeFactory;

/**
 * Unit test for RationalMatrix Class
 */
class MatrixTest extends \PHPUnit_Framework_TestCase
{

    const NSUT = 'chippyash\Math\Matrix\RationalMatrix';

    /**
     * @var Matrix
     */
    protected $object;

    public function testConstructWithRationalMatrixMatrixGivesRationalMatrix()
    {
        $this->object = new RationalMatrix(new RationalMatrix([]));
        $this->assertInstanceOf(self::NSUT, $this->object);
    }

    public function testConstructNonEmptyArrayGivesNonEmptyMatrix()
    {
        $this->object = new RationalMatrix(array(2));
        $this->assertInstanceOf(self::NSUT, $this->object);
        $this->assertFalse($this->object->is('empty'));
    }

    public function testConstructSingleItemArrayGivesSingleItemMatrix()
    {
        $test = [1];
        $expected = [[RationalTypeFactory::create(1)]];

        $this->object = new RationalMatrix($test);
        $this->assertEquals($expected, $this->object->toArray());
    }


    public function testMatrixGetReturnsCorrectValue()
    {
        $testArray = array(array(1, 2, 3), array(0, 2, 1), array(2.5, 1, 3));
        $this->object = new RationalMatrix($testArray);
        for ($r = 1; $r < 4; $r++) {
            for ($c = 1; $c < 4; $c++) {
                $this->assertEquals(
                        RationalTypeFactory::create($testArray[$r - 1][$c - 1]),
                        $this->object->get($r, $c));
            }
        }
    }

    /**
     * @covers chippyash\Matrix\Matrix::compute()
     */
    public function testComputeReturnsCorrectResult()
    {
        $testArray = [[1, 2, 3], [3, 2, 1], [2, 1, 3]];
        $expectedArray = [
                [RationalTypeFactory::create(3), RationalTypeFactory::create(4), RationalTypeFactory::create(5)],
                [RationalTypeFactory::create(5), RationalTypeFactory::create(4), RationalTypeFactory::create(3)],
                [RationalTypeFactory::create(4), RationalTypeFactory::create(3), RationalTypeFactory::create(5)]];
        $object = new RationalMatrix($testArray);
        $computation = new \chippyash\Math\Matrix\Computation\Add\Scalar();
        $this->assertEquals($expectedArray, $object->compute($computation, 2)->toArray());
    }
}
