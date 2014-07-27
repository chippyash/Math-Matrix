<?php

namespace chippyash\Test\Math\Matrix;

use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Matrix\Matrix;
use chippyash\Type\Number\IntType;
use chippyash\Type\Number\FloatType;
use chippyash\Type\Number\WholeIntType;
use chippyash\Type\Number\NaturalIntType;
use chippyash\Type\Number\Rational\RationalType;
use chippyash\Type\Number\Complex\ComplexType;

/**
 * Unit test for NumericMatrix Class
 */
class NumericMatrixTest extends \PHPUnit_Framework_TestCase
{

    const NSUT = 'chippyash\Math\Matrix\NumericMatrix';

    /**
     * @var Matrix
     */
    protected $object;

    /**
     * @expectedException chippyash\Math\Matrix\Exceptions\MathMatrixException
     * @expectedExceptionMessage NumericMatrix expects NumericMatrix or array as source data
     */
    public function testConstructWithBaseMatrixThrowsException()
    {
        $this->object = new NumericMatrix(new Matrix([]));
    }
    
    public function testConstructWithNumericMatrixIsAllowed()
    {
        $this->assertInstanceOf(self::NSUT, new NumericMatrix(new NumericMatrix([])));
    }
    
    public function testConstructNonEmptyArrayGivesNonEmptyMatrix()
    {
        $this->object = new NumericMatrix(array(2));
        $this->assertInstanceOf(self::NSUT, $this->object);
        $this->assertFalse($this->object->is('empty'));
    }

    public function testConstructSingleItemArrayGivesSingleItemMatrix()
    {
        $test = [1];
        $expected = [[new IntType(1)]];

        $this->object = new NumericMatrix($test);
        $this->assertEquals($expected, $this->object->toArray());
    }

    /**
     * @dataProvider completeArrays
     */
    public function testConstructWithGoodArraysGivesNumericMatrix($testArray)
    {
        $mA = new NumericMatrix($testArray);
        $this->assertInstanceOf(self::NSUT, $mA);
    }

    /**
     *
     * @return array [[testArray], ...]
     */
    public function completeArrays()
    {
        return [
            [[]], //shorthand empty array
            [[[]]], //longhand empty array
            [[1]], //shorthand single vertice array
            [[[1]]], //longhand single vertice array
            [[[1, 2], [2, 1]]], //even number array
            [[[1.12, 2, 3], [3, 2, 1], [2, 1, 3]]], //odd number array
        ];
    }

    /**
     * @dataProvider differentNumberTypeArrays
     */
    public function testConstructWithDifferentNumberTypesGivesNumericMatrix($testArray, $expectedArray)
    {
        $this->object = new NumericMatrix($testArray);
        $this->assertEquals($expectedArray, $this->object->toArray());
    }

    /**
     *
     * @return array [[testArray, expectedArray], ...]
     */
    public function differentNumberTypeArrays()
    {
        return [
            [[1], [[new IntType(1)]]], //php ints cast to IntType
            [[2.5], [[new RationalType(new IntType(5),new IntType(2))]]], //php floats cast to RationalType
            [[new IntType(1)], [[new IntType(1)]]],
            [[new FloatType(2.3)], [[new FloatType(2.3)]]],
            [[new WholeIntType(1)], [[new WholeIntType(1)]]],
            [[new NaturalIntType(1)], [[new NaturalIntType(1)]]],
            [[new RationalType(new IntType(5),new IntType(2))],[[new RationalType(new IntType(5),new IntType(2))]]],
        ];
    }

    /**
     * @dataProvider nonCompleteArrays
     */
    public function testConstructGivesNormalizedMatrix($testArray, $expectedArray)
    {
        $this->object = new NumericMatrix($testArray, 1);
        $this->assertEquals($expectedArray, $this->object->toArray());
    }

    /**
     *
     * @return array [[$testArray, $expectedArray],...]
     */
    public function nonCompleteArrays()
    {
        return [
            [[], [[]]], //empty array
            [[2], [[new IntType(2)]]], //single vertice
            [[[2, 1], [2]], [[new IntType(2), new IntType(1)], [new IntType(2), new IntType(1)]]], //missing X2.Y2
            [[[2], [2, 1]], [[new IntType(2), new IntType(1)], [new IntType(2), new IntType(1)]]], //missing X1.Y2
            [[[], [2, 1]], [[new IntType(1), new IntType(1)], [new IntType(2), new IntType(1)]]], //missing X1.Y1, X1.Y2
            [[[2, 1], []], [[new IntType(2), new IntType(1)], [new IntType(1), new IntType(1)]]], //missing X2.Y1, X2.Y2
        ];
    }

    public function testComputeReturnsCorrectResult()
    {
        $testArray = [[1, 2, 3], [3, 2, 1], [2, 1, 3]];
        $three = new IntType(3);
        $four = new IntType(4);
        $five = new IntType(5);
        $expectedArray = [[$three, $four, $five], [$five, $four, $three], [$four, $three, $five]];
        $object = new NumericMatrix($testArray);
        $computation = new \chippyash\Math\Matrix\Computation\Add\Scalar();
        $this->assertEquals($expectedArray, $object->compute($computation, 2)->toArray());
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid operation name
     */
    public function testInvokeWithBadComputationNameThrowsException()
    {
        $mA = new NumericMatrix([]);
        $mA('foobar');
    }

    public function testInvokeProxiesToComputation()
    {
        $testArray = [[1, 2, 3], [3, 2, 1], [2, 1, 3]];
        $three = new IntType(3);
        $four = new IntType(4);
        $five = new IntType(5);
        $expectedArray = [[$three, $four, $five], [$five, $four, $three], [$four, $three, $five]];
        $object = new NumericMatrix($testArray);
        $this->assertEquals($expectedArray, $object("Add\\Scalar", 2)->toArray());
    }

}
