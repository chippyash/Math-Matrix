<?php
/**
 * Math-Matrix
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2016, UK
 * @license BSD 3 Clause See LICENSE.md
 */
namespace Chippyash\Test\Math\Matrix\Special;

use Chippyash\Math\Matrix\Formatter\AsciiNumeric;
use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Math\Matrix\Special\Cauchy;
use Chippyash\Type\RequiredType;

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

    public function testYouCanCreateACauchyMatrixWithASingleIntegerXParameter()
    {
        $test = $this->sut->create([3]);
        echo $test->setFormatter(new AsciiNumeric())->display();
    }
}
