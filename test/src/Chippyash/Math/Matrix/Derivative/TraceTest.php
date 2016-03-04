<?php
namespace Chippyash\Test\Math\Matrix\Derivative;
use Chippyash\Math\Matrix\Derivative\Trace;
use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Type\RequiredType;

/**
 */
class TraceTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        RequiredType::getInstance()->set(RequiredType::TYPE_NATIVE);
        $this->object = new Trace();
    }

    public function testSutHasDerivativeInterface()
    {
        $this->assertInstanceOf(
                'Chippyash\Math\Matrix\Interfaces\DerivativeInterface',
                $this->object);
    }

    /**
     * @expectedException Chippyash\Matrix\Exceptions\MatrixException
     * @expectedExceptionMessage No trace for empty matrix
     */
    public function testEmptyMatrixThrowsException()
    {
        $mA = new NumericMatrix(array());
        $this->object->derive($mA);
    }

    public function testSingleItemMatrixReturnsSoleVertice()
    {
        $mA = new NumericMatrix(array(1));
        $this->assertEquals(1, $this->object->derive($mA)->get());
    }

    /**
     * @expectedException Chippyash\Matrix\Exceptions\MatrixException
     * @expectedExceptionMessage No trace for non-square matrix
     */
    public function testNonSquareMatrixThrowsException()
    {
        $mA = new NumericMatrix(array(1,2));
        $this->object->derive($mA);
    }

    public function testReturnsTraceForSquareMatrix()
    {
       $mA = new NumericMatrix(
               array(
                   array(1,2,3),
                   array(4,5,6),
                   array(7,8,9)
               ));
       $this->assertEquals(15, $this->object->derive($mA)->get());
    }
}
