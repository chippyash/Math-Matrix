<?php
namespace chippyash\Test\Math\Matrix\Computation\Logic;
use chippyash\Math\Matrix\Computation\Logic\XorMatrix;
use chippyash\Math\Matrix\Matrix;

/**
 *
 */
class XorMatrixTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new XorMatrix();
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
     * @return array [[dataA, dataB, expected],...]
     */
    public function computeMatrices()
    {
        return array(
            array(
                array(array(true,false)),
                array(array(true,true)),
                array(array(false, true)),
            ),
            array(
                array(array(true,false)),
                array(array(false,false)),
                array(array(true, false)),
            ),
            array(
                array(array(1,0)),
                array(array(1,1)),
                array(array(false, true)),
            ),
            array(
                array(array(1,0)),
                array(array(0,0)),
                array(array(true, false)),
            ),
        );
    }
}
