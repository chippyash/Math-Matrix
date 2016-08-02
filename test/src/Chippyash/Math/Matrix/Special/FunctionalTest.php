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
use Chippyash\Math\Matrix\Special\Functional;
use Chippyash\Type\RequiredType;
use Chippyash\Math\Matrix\MatrixFactory;

class FunctionalTest extends \PHPUnit_Framework_TestCase
{
    /**
     * System Under Test
     * @var Functional
     */
    protected $sut;

    protected function setUp()
    {
        RequiredType::getInstance()->set(RequiredType::TYPE_NATIVE);
        $this->sut = new Functional();
    }

    /**
     * @expectedException \Chippyash\Validation\Exceptions\InvalidParameterException
     * @expectedExceptionMessage Invalid or missing parameter: Value key:rows is not of type:integer:Value has invalid type map
     */
    public function testRowsParameterMustBeAnInteger()
    {
        $this->sut->create(['foo']);
    }

    /**
     * @expectedException \Chippyash\Validation\Exceptions\InvalidParameterException
     * @expectedExceptionMessage Invalid or missing parameter: Value key:cols is not of type:integer:Value has invalid type map
     */
    public function testColsParameterMustBeAnInteger()
    {
        $this->sut->create([1, 'foo']);
    }

    /**
     * @expectedException \Chippyash\Validation\Exceptions\InvalidParameterException
     * @expectedExceptionMessage Invalid or missing parameter: Value key:f did not return true from a function call:Value has invalid type map
     */
    public function testFParameterMustBeAClosure()
    {
        $this->sut->create([1, 1, 'foo']);
    }

    public function testReturnedMatrixWillBeAFunctionOfTheFParameter()
    {
        $test = $this->sut->create([3, 3, function($row, $col) {return 1;}]);
        $expected = new NumericMatrix(
            [
                [1, 1, 1],
                [1, 1, 1],
                [1, 1, 1]
            ]
        );

        $this->assertTrue($test->equality($expected, false));
    }
}
