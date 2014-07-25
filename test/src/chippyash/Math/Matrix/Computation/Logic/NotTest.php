<?php
namespace chippyash\Test\Math\Matrix\Computation\Logic;
use chippyash\Math\Matrix\Computation\Logic\Not;
use chippyash\Math\Matrix\Matrix;

/**
 *
 * @author akitson
 */
class NotTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new Not();
    }

    public function testComputeReturnsEmptyIfMatrixIsEmpty()
    {
        $m = new Matrix(array());
        $test = $this->object->compute($m);
        $this->assertTrue($test->is('empty'));
    }

    /**
     * @dataProvider computeMatrices
     */
    public function testComputeReturnsCorrectResult($test, $expected)
    {
        $m = new Matrix($test);
        $this->assertEquals($expected, $this->object->compute($m)->toArray());
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
                array(array(false,true,false,false)),
            ),
            array(
                array(array(-1.2,0.0,1.2,2.2)),
                array(array(false,true,false,false)),
            ),
            array(
                array(array(2)),
                array(array(false)),
            ),
            array(
                array(array(true, false)),
                array(array(false, true)),
            ),
        );
    }
}
