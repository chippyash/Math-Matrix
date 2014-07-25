<?php
namespace chippyash\Test\Math\Matrix\Derivative;
use chippyash\Math\Matrix\Derivative\Trace;
use chippyash\Math\Matrix\Matrix;

/**
 */
class TraceTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new Trace();
    }

    public function testSutHasDerivativeInterface()
    {
        $this->assertInstanceOf(
                'chippyash\Matrix\Interfaces\DerivativeInterface',
                $this->object);
    }

    /**
     * @covers chippyash\Matrix\Derivative\Trace::derive()
     * @expectedException chippyash\Matrix\Exceptions\UndefinedComputationException
     * @expectedExceptionMessage Computation Error: Undefined computation: No trace for empty matrix
     */
    public function testEmptyMatrixThrowsException()
    {
        $mA = new Matrix(array());
        $this->object->derive($mA);
    }

    /**
     * @covers chippyash\Matrix\Derivative\Trace::derive()
     */
    public function testSingleItemMatrixReturnsSoleVertice()
    {
        $mA = new Matrix(array(1));
        $this->assertEquals(1, $this->object->derive($mA));
    }

    /**
     * @covers chippyash\Matrix\Derivative\Trace::derive()
     * @expectedException chippyash\Matrix\Exceptions\UndefinedComputationException
     * @expectedExceptionMessage Computation Error: Undefined computation: No trace for non-square matrix
     */
    public function testNonSquareMatrixThrowsException()
    {
        $mA = new Matrix(array(1,2));
        $this->object->derive($mA);
    }

    /**
     * @covers chippyash\Matrix\Derivative\Trace::derive()
     */
    public function testReturnsTraceForSquareMatrix()
    {
       $mA = new Matrix(
               array(
                   array(1,2,3),
                   array(4,5,6),
                   array(7,8,9)
               ));
       $this->assertEquals(15, $this->object->derive($mA));
    }
}
