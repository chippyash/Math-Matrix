<?php
namespace chippyash\Test\Math\Matrix\Computation\Sub;
use chippyash\Math\Matrix\Computation\Sub\Scalar;
use chippyash\Math\Matrix\NumericMatrix;

/**
 * Description of ScalarTest
 *
 * @author akitson
 */
class ScalarTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new Scalar();
    }

    public function testComputeAcceptsNumericScalarValue()
    {
        $m = new NumericMatrix(array(1));
        $this->object->compute($m, 0);
        $this->object->compute($m, 1.23);
        $this->object->compute($m, '2/3');
        $this->object->compute($m, true);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\MatrixException
     * @expectedExceptionMessage Scalar parameter is not a supported type for numeric matrices
     */
    public function testComputeRejectsNonNumericScalarValue()
    {
        $this->object->compute(new NumericMatrix(array(1)), 'foo');
    }

    /**
     * @expectedException chippyash\Math\Matrix\Exceptions\ComputationException
     * @dataProvider nonScalarValues
     */
    public function testComputeRejectsNonScalarValue($nonScalar)
    {
        $m = new NumericMatrix(array(1));
        $this->object->compute($m, $nonScalar);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\MatrixException
     * @expectedExceptionMessage NumberToNumeric expects int, float, string or Rational
     * @dataProvider nonScalarValues
     */
    public function testComputeRejectsNonScalarValueInMatrix($nonScalar)
    {
        $m = new NumericMatrix(array($nonScalar));
        $this->object->compute($m, 1);
    }

    public function nonScalarValues()
    {
        return array(
            array(array(array())),  //array
            array(new \stdClass()), //object
            array(tmpfile())        //resource
        );
    }

    public function testComputeReturnsEmptyIfMatrixIsEmpty()
    {
        $m = new NumericMatrix(array());
        $test = $this->object->compute($m, 1);
        $this->assertTrue($test->is('empty'));
    }

    /**
     * @dataProvider computeMatrices
     */
    public function testComputeReturnsCorrectResult($operand, $test, $scalar)
    {
        $mA = new NumericMatrix($operand);
        $mT = new NumericMatrix($test);
        $this->assertEquals($mT, $this->object->compute($mA, $scalar));
    }

    public function computeMatrices()
    {
        return array(
            array(
                array(array(1,2,3), array(3,2,1), array(2,1,3)),
                array(array(-1,0,1), array(1,0,-1), array(0,-1,1)),
                2
            ),
              array(
                array(array(1,2,3), array(3,2,1), array(2,1,3)),
                array(array(-1.5,-0.5,0.5), array(0.5,-0.5,-1.5), array(-0.5,-1.5,0.5)),
                2.5
            ),
              array(
                array(array(1.5,2.5,3.5), array(3.5,2.5,1.5), array(2.5,1.5,3.5)),
                array(array(-0.5,0.5,1.5), array(1.5,0.5,-0.5), array(0.5,-0.5,1.5)),
                2
            ),
              array(
                array(array(1.12,2.12,3.12), array(3.12,2.12,1.12), array(2.12,1.12,3.12)),
                array(array(-1.0,0.0,1.0), array(1.0,0.0,-1.0), array(0.0,-1.0,1.0)),
                2.12
            ),
            array(
                array(array(1,2,3), array(3,2,1), array(2,1,3)),
                array(array(0,1,2), array(2,1,0), array(1,0,2)),
                true
            ),
            array(
                array(array(1,2,3), array(3,2,1), array(2,1,3)),
                array(array(1,2,3), array(3,2,1), array(2,1,3)),
                false
            ),
            array(
                array(array(true,false)),
                array(array(0, -1)),
                true
            ),
            array(
                array(array(true,false)),
                array(array(1, 0)),
                false
            ),
        );
    }
}
