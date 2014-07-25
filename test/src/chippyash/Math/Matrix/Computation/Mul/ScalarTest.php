<?php
namespace chippyash\Test\Math\Matrix\Computation\Mul;
use chippyash\Math\Matrix\Computation\Mul\Scalar;
use chippyash\Math\Matrix\Matrix;

/**
 * Multiplication by scalar test
 */
class ScalarTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new Scalar();
    }

    public function testComputeReturnsEmptyIfMatrixIsEmpty()
    {
        $m = new Matrix(array());
        $test = $this->object->compute($m, 1);
        $this->assertTrue($test->is('empty'));
    }

    public function testComputeAcceptsNumericScalarValue()
    {
        $m = new Matrix([1]);
        $this->object->compute($m, 0);
        $this->object->compute($m, 1.23);
        $this->object->compute($m, true);
        $this->object->compute($m, '2/3');
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\MatrixException
     * @expectedExceptionMessage The string representation of the number is invalid for a rational
     */
    public function testComputeRejectsStringValue()
    {
        $m = new Matrix([1]);
        $this->object->compute($m, 'foo');

    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Parameter is not scalar!
     * @dataProvider nonScalarValues
     */
    public function testComputeRejectsNonScalarValue($nonScalar)
    {
        $m = new Matrix([1]);
        $this->object->compute($m, $nonScalar);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\MatrixException
     * @expectedExceptionMessage Rational expects int, float, string or Rational
     * @dataProvider nonScalarValues
     */
    public function testComputeRejectsNonScalarValueInMatrix($nonScalar)
    {
        $m = new Matrix(array($nonScalar));
        $this->object->compute($m, 1);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\MatrixException
     * @expectedExceptionMessage The string representation of the number is invalid for a rational
     */
    public function testComputeRejectsStringValueInMatrix()
    {
        $m = new Matrix(array('foo'));
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


    /**
     * @dataProvider computeMatrices
     */
    public function testComputeReturnsCorrectResult($operand, $test, $scalar)
    {
        $m = new Matrix($operand);
        $this->assertEquals($test, $this->object->compute($m, $scalar)->toArray());
    }

    public function computeMatrices()
    {
        return array(
            array(
                array(array(1,2,3), array(3,2,1), array(2,1,3)),
                array(array(2,4,6), array(6,4,2), array(4,2,6)),
                2
            ),
              array(
                array(array(1,2,3)),
                array(array(2.5,5.0,7.5)),
                2.5
            ),
              array(
                array(array(1.5,2.5,3.5)),
                array(array(3.0,5.0,7.0)),
                2
            ),
              array(
                array(array(1.12,2.12,3.12)),
                array(array(1.12,2.12,3.12)),
                1.0
            ),
            array(
                array(array(1,2,3)),
                array(array(1,2,3)),
                true
            ),
            array(
                array(array(1,2,3)),
                array(array(0,0,0)),
                false
            ),
            array(
                array(array(true,false)),
                array(array(1, 0)),
                true
            ),
            array(
                array(array(true,false)),
                array(array(0, 0)),
                false
            ),
        );
    }
}
