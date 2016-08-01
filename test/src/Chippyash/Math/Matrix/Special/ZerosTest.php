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
use Chippyash\Math\Matrix\Special\Zeros;
use Chippyash\Type\RequiredType;

class ZerosTest extends \PHPUnit_Framework_TestCase
{
    /**
     * System Under Test
     * @var Zeros
     */
    protected $sut;

    protected function setUp()
    {
        RequiredType::getInstance()->set(RequiredType::TYPE_NATIVE);
        $this->sut = new Zeros();
    }

    /**
     * @expectedException \Chippyash\Validation\Exceptions\InvalidParameterException
     * @expectedExceptionMessage Invalid or missing parameter: Value key:rows is not of type:integer:Value has invalid type map:Value key:rows is not of type:integer:Value has invalid type map
     */
    public function testIfOneArgumentSuppliedToCreateMethodItMustBeAnInteger()
    {
        $this->sut->create(['foo']);
    }

    /**
     * @expectedException \Chippyash\Validation\Exceptions\InvalidParameterException
     * @expectedExceptionMessage Invalid or missing parameter: rows parameter must be integer > 0:Value key:cols is not of type:integer:Value has invalid type map
     */
    public function testIfTwoArgumentsSuppliedToCreateTheyMustBothBeIntegers()
    {
        $this->sut->create([1, 'foo']);
    }


    public function testYouCanCreateAZeroesColumnVectorWithASingleIntegerRowsParameter()
    {
        $test = $this->sut->create([3]);
        $expected = new NumericMatrix([
            [0],
            [0],
            [0]
        ]);
        $this->assertTrue($test->equality($expected, false));
    }

    public function testYouCanCreateAZeroesRowMatrixWithTwoIntegerParamaters()
    {
        $test = $this->sut->create([1,3]);
        $expected = new NumericMatrix([[0,0,0]]);
        $this->assertTrue($test->equality($expected, false));
    }

    public function testYouCanCreateAZeroesMatrixWithTwoIntegerParametersGreaterThanOne()
    {
        $test = $this->sut->create([3,3]);
        $expected = new NumericMatrix([
            [0,0,0],
            [0,0,0],
            [0,0,0]]);
        $this->assertTrue($test->equality($expected, false));
    }
}
