<?php
/**
 * Math-Matrix
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2016, UK
 * @license BSD 3 Clause See LICENSE.md
 */
namespace Chippyash\Test\Math\Matrix\Special;

use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Math\Matrix\Special\Cauchy;
use Chippyash\Type\RequiredType;
use Chippyash\Math\Matrix\MatrixFactory;

class CauchyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * System Under Test
     * @var Cauchy
     */
    protected $sut;

    protected function setUp()
    {
        RequiredType::getInstance()->set(RequiredType::TYPE_NATIVE);
        $this->sut = new Cauchy();
    }

    /**
     * @expectedException \Chippyash\Validation\Exceptions\InvalidParameterException
     * @expectedExceptionMessage Invalid or missing parameter: Value key:X is not of type:integer:Value has invalid type map:Value key:X is not of type:Chippyash\Math\Matrix\NumericMatrix:Value has invalid type map
     */
    public function testIfOneArgumentSuppliedToCreateMethodItMustBeAnInteger()
    {
        $this->sut->create(['foo']);
    }

    /**
     * @expectedException \Chippyash\Validation\Exceptions\InvalidParameterException
     * @expectedExceptionMessage Value key:X is not of type:integer:Value has invalid type map:Value key:Y is not of type:Chippyash\Math\Matrix\NumericMatrix:Value has invalid type map
     */
    public function testIfTwoArgumentsSuppliedToCreateTheyMustBothBeNumericMatrices()
    {
        $this->sut->create([new NumericMatrix([1,2,3]), 'foo']);
    }

    /**
     * @expectedException \Chippyash\Validation\Exceptions\InvalidParameterException
     * @expectedExceptionMessage Value key:X is not of type:integer:Value has invalid type map:X and Y must be vectors
     */
    public function testIfTwoArgumentsSuppliedToCreateTheyMustBothBeVectors()
    {
        $mX = new NumericMatrix(
            [
                [1,2],
                [3,4],
                [5,6]
            ]
        );
        $mY = new NumericMatrix(
            [
                [1,2,3]
            ]
        );

        $this->sut->create([$mX, $mY]);
    }

    /**
     * @expectedException \Chippyash\Validation\Exceptions\InvalidParameterException
     * @expectedExceptionMessage Value key:X is not of type:integer:Value has invalid type map:X and Y must be vectors of same length for cauchy matrix
     */
    public function testIfTwoArgumentsSuppliedToCreateTheyMustBothBeVectorsOfTheSameLength()
    {
        $mX = new NumericMatrix(
            [
                [1],
                [3],
                [5]
            ]
        );
        $mY = new NumericMatrix(
            [
                [1,2,3,4]
            ]
        );

        $this->sut->create([$mX, $mY]);
    }

    public function testYouCanCreateACauchyMatrixWithASingleIntegerXParameter()
    {
        $test = $this->sut->create([3]);
        $expected = MatrixFactory::create('rational', [
            ['1/2', '1/3', '1/4'],
            ['1/3', '1/4', '1/5'],
            ['1/4', '1/5', '1/6']
        ]);
        $this->assertTrue($test->equality($expected, false));
    }

    public function testYouCanCreateACauchyMatrixWithTwoVectorParamaters()
    {
        $mX = new NumericMatrix([[1,2,3]]);
        $mY = new NumericMatrix([[3,4,5]]);
        $expected = MatrixFactory::create('rational', [
            ['1/4', '1/5', '1/6'],
            ['1/5', '1/6', '1/7'],
            ['1/6', '1/7', '1/8']
        ]);
        $test = $this->sut->create([$mX, $mY]);
        $this->assertTrue($test->equality($expected, false));
    }
}
