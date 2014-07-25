<?php
namespace chippyash\Test\Math\Matrix\Computation\Logic;
use chippyash\Math\Matrix\Computation\Logic\AndMatrix;
use chippyash\Math\Matrix\Matrix;

/**
 *
 * @author akitson
 */
class AndMatrixTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new AndMatrix();
    }

    /**
     * @dataProvider computeMatrices
     */
    public function testComputeReturnsCorrectResult($aA, $aB, $expected)
    {
        $mA = new Matrix($aA);
        $mB = new Matrix($aB);
        $this->assertEquals($expected, $this->object->compute($mA, $mB)->toArray());
    }

    /**
     * Data provider
     * @return array [[test, expected, matrix],...]
     */
    public function computeMatrices()
    {
        return array(
            array(
                array(array(true,false)),
                array(array(true,true)),
                array(array(true, false)),
            ),
            array(
                array(array(true,false)),
                array(array(false,false)),
                array(array(false, false)),
            ),
            array(
                array(array(1,0)),
                array(array(1,1)),
                array(array(true, false)),
            ),
            array(
                array(array(1,0)),
                array(array(0,0)),
                array(array(false, false)),
            ),
        );
    }
}
