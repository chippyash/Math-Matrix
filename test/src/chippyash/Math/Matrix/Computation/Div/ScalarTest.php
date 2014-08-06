<?php
namespace chippyash\Test\Math\Matrix\Computation\Div;
use chippyash\Math\Matrix\Computation\Div\Scalar;
use chippyash\Math\Matrix\NumericMatrix;

/**
 * Division by scalar test
 */
class ScalarTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new Scalar();
    }

    public function testComputeReturnsEmptyMatrixIfMatrixParameterIsEmpty()
    {
        $this->assertEquals(true, $this->object->compute(new NumericMatrix([]),0)->is('empty'));
    }

    /**
     * @expectedException chippyash\Math\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Divisor == zero
     */
    public function testComputeThrowsExceptionIfScalarIsZero()
    {
        $mA = new NumericMatrix(array(2));
        $this->object->compute($mA, 0);
    }

    /**
     * @expectedException chippyash\Math\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Divisor == zero
     */
    public function testComputeThrowsExceptionIfScalarIsBooleanFalse()
    {
        $mA = new NumericMatrix(array(2));
        $this->object->compute($mA, false);
    }

    /**
     * expectedException chippyash\Matrix\Exceptions\MatrixException
     * @expectedExceptionMessage Computation Error: Parameter is a string
     */
    public function testComputeRejectsStringValue()
    {
        $m = new NumericMatrix(array());
        $this->object->compute($m, 'foo');

    }

    /**
     * @expectedException chippyash\Math\Matrix\Exceptions\ComputationException
     * @dataProvider nonScalarValues
     */
    public function testComputeRejectsNonScalarValue($nonScalar)
    {
        $m = new NumericMatrix([[3]]);
        $this->object->compute($m, $nonScalar);
    }

    public function nonScalarValues()
    {
        return array(
            array(null),            //null
            array(array(array())),  //array
            array(new \stdClass()), //object
            array(tmpfile())        //resource
        );
    }

    /**
     * @dataProvider computeMatrices
     */
    public function testComputeReturnsCorrectResult($operand, $test, $scalar)
    {
        $mA = new NumericMatrix($operand);
        $aC = $this->object->compute($mA, $scalar)->toArray();
        $rows = $mA->rows();
        $cols = $mA->columns();
        for ($r=0;$r<$rows;$r++) {
            for ($c=0;$c<$cols;$c++) {
                $this->assertEquals($aC[$r][$c](), $test[$r][$c]);
            }
        }
    }

    public function computeMatrices()
    {
        return [
            [
                [[1,2,3], [3,2,1], [2,1,3]],
                [[0.5,1,1.5], [1.5,1,0.5], [1,0.5,1.5]],
                2
            ],
              [
                [[1,2,3]],
                [[1/2.5,2/2.5,3/2.5]],
                2.5
            ],
              [
                [[1.12,2.12,3.12]],
                [[1.12,2.12,3.12]],
                1.0
            ],
            [
                [[1,2,3]],
                [[1,2,3]],
                true
            ],
            [
                [[true,false]],
                [[1, 0]],
                true
            ],
        ];
    }
}
