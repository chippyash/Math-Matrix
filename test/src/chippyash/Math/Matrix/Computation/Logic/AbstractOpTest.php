<?php
namespace chippyash\Test\Math\Matrix\Computation\Logic;
use chippyash\Math\Matrix\Matrix;

/**
 *
 * @author akitson
 */
class AbstractOpTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = $this->getMockForAbstractClass('chippyash\Matrix\Computation\Logic\AbstractOp');
        $this->object
                ->expects($this->any())
                ->method('doComputation')
                ->will($this->returnValue(new Matrix(array())));
    }

    public function testComputeAcceptsBooleanOperand()
    {
        $m = new Matrix(array());
        $this->object->compute($m, true);
        $this->object->compute($m, false);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Operand parameter is not boolean!
     * @dataProvider nonBoolValues
     */
    public function testComputeRejectsNonBooleanOperand($operand)
    {
        $m = new Matrix(array(1));
        $this->object->compute($m, $operand);
    }

    /**
     * Data provider
     * @return array [[operand],...]
     */
    public function nonBoolValues()
    {
        return array(
            array('foo'),
            array(2),
            array(2.23),
            array(array()),
            array(tmpfile()),
            array(new \stdClass()),
        );
    }

    public function testComputeReturnsEmptyIfMatrixIsEmpty()
    {
        $m = new Matrix(array());
        $test = $this->object->compute($m, true);
        $this->assertTrue($test->is('empty'));
    }
}
