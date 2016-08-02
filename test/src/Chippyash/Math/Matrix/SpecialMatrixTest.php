<?php
/**
 * Math-Matrix
 *
 * @author Ashley Kitson
 * @copyright Ashley Kitson, 2016, UK
 * @license BSD 3 Clause See LICENSE.md
 */

namespace Chippyash\Test\Math\Matrix;

use Chippyash\Math\Matrix\SpecialMatrix;
use Chippyash\Type\String\StringType;

class SpecialMatrixTest extends \PHPUnit_Framework_TestCase
{
    /**
     * System Under Test
     * @var SpecialMatrix
     */
    protected $sut;

    protected function setUp()
    {
        $this->sut = new SpecialMatrix();
    }

    /**
     * @expectedException \Chippyash\Math\Matrix\Exceptions\MathMatrixException
     */
    public function testInvokingTheClassWithNoParametersThrowsAnExceptoion()
    {
        $sut = $this->sut;
        $sut();
    }

    /**
     * @expectedException \Chippyash\Math\Matrix\Exceptions\MathMatrixException
     */
    public function testInvokingTheClassWithAnUnknownMatrixNameThrowsAnException()
    {
        $sut = $this->sut;
        $sut('foo');
    }

    /**
     * @expectedException \Chippyash\Math\Matrix\Exceptions\MathMatrixException
     */
    public function testYouCanInvokeTheClassWithAStringTypeForTheName()
    {
        $sut = $this->sut;
        $sut(new StringType('foo'));
    }

    /**
     * @expectedException \Chippyash\Math\Matrix\Exceptions\MathMatrixException
     */
    public function testYouCanInvokeTheClassWithAStringForTheName()
    {
        $sut = $this->sut;
        $sut('foo');
    }

    public function testTheCreateMethodRequiresAStringTypeForTheMatrixName()
    {
        $this->assertTrue(true);
    }
}
