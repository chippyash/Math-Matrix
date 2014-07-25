<?php
namespace chippyash\Test\Math\Matrix\Computation\Logic;
use chippyash\Math\Matrix\Computation\Logic\OrOperand;
use chippyash\Math\Matrix\Matrix;

/**
 *
 * @author akitson
 */
class OrOperandTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new OrOperand();
    }

    /**
     * @dataProvider computeMatrices
     */
    public function testComputeReturnsCorrectResult($test, $expected, $operand)
    {
        $m = new Matrix($test);
        $this->assertEquals($expected, $this->object->compute($m, $operand)->toArray());
    }

    /**
     * Data provider
     * @return array [[test, expected, operand],...]
     */
    public function computeMatrices()
    {
        return array(
            array(
                array(array(-1,0,1,2)),
                array(array(true,true,true,true)),
                true
            ),
            array(
                array(array(-1,0,1,2)),
                array(array(true,false,true,true)),
                false
            ),
            array(
                array(array(-1.2,0.0,1.2,2.2)),
                array(array(true,true,true,true)),
                true
            ),
            array(
                array(array(-1.2,0.0,1.2,2.2)),
                array(array(true,false,true,true)),
                false
            ),
            array(
                array(array(2)),
                array(array(true)),
                true
            ),
            array(
                array(array(2)),
                array(array(true)),
                false
            ),
            array(
                array(array(true, false)),
                array(array(true, true)),
                true
            ),
            array(
                array(array(true, false)),
                array(array(true, false)),
                false
            ),
        );
    }
}
