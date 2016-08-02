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
use Chippyash\Math\Matrix\Special\Identity;
use Chippyash\Type\RequiredType;

class IdentityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * System Under Test
     * @var Identity
     */
    protected $sut;

    protected function setUp()
    {
        RequiredType::getInstance()->set(RequiredType::TYPE_NATIVE);
        $this->sut = new Identity();
    }

    /**
     * @expectedException \Chippyash\Validation\Exceptions\InvalidParameterException
     * @expectedExceptionMessage Invalid or missing parameter: Value key:size is not of type:integer:Value has invalid type map
     */
    public function testArgumentSuppliedToCreateMethodMustBeAnInteger()
    {
        $this->sut->create(['foo']);
    }

    public function testYouCanCreateAnIdentityMatrixWithASingleIntegerSizeParameter()
    {
        $test = $this->sut->create([3]);
        $expected = new NumericMatrix([
            [1, 0, 0],
            [0, 1, 0],
            [0, 0, 1]
        ]);
        $this->assertTrue($test->equality($expected, false));
    }

    /**
     * @expectedException \Chippyash\Validation\Exceptions\InvalidParameterException
     */
    public function testSizeParameterMustBeGreaterThanZero()
    {
        $this->sut->create([0]);

    }

    public function testYouCanCreateASingleItemIdentityMatrix()
    {
        $expected = new NumericMatrix(
            [
                [1]
            ]
        );
        $test = $this->sut->create([1]);
        $this->assertTrue($test->equality($expected, false));
    }
}
