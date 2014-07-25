<?php
namespace chippyash\Test\Math\Matrix\Computation\Logic;
use chippyash\Math\Matrix\Computation\Logic\OrMatrix;
use chippyash\Math\Matrix\Matrix;

/**
 *
 */
class OrMatrixTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new OrMatrix();
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
                array(array(true, true)),
            ),
            array(
                array(array(true,false)),
                array(array(false,false)),
                array(array(true, false)),
            ),
            array(
                array(array(1,0)),
                array(array(1,1)),
                array(array(true, true)),
            ),
            array(
                array(array(1,0)),
                array(array(0,0)),
                array(array(true, false)),
            ),
        );
    }
}
