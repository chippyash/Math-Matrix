<?php
namespace chippyash\Test\Math\Matrix\Computation\Div;
use chippyash\Math\Matrix\Computation\Div\Scalar;
use chippyash\Math\Matrix\Matrix;

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
        $this->assertEquals(true, $this->object->compute(new Matrix([]),0)->is('empty'));
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Matrix is not rational
     */
    public function testComputeRequiresMatrixParameterToBeRational()
    {
        $this->object->compute(new Matrix([1], false, false, null, false), 1);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error
     */
    public function testComputeRequiresMatrixParameterToBeComplete()
    {
        var_dump(new Matrix([[1,2],[2]]));
        $this->object->compute(new Matrix([[1,2],[2]]), 1);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Divisor == zero
     */
    public function testComputeThrowsExceptionIfScalarIsZero()
    {
        $mA = new Matrix(array(2));
        $this->object->compute($mA, 0);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Divisor == zero
     */
    public function testComputeThrowsExceptionIfScalarIsBooleanFalse()
    {
        $mA = new Matrix(array(2));
        $this->object->compute($mA, false);
    }

    public function testComputeAcceptsNonStringScalarValue()
    {
        $m = new Matrix(array());
        $this->object->compute($m, 1.23);
        $this->object->compute($m, true);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\MatrixException
     * @expectedExceptionMessage Computation Error: Parameter is a string
     */
    public function testComputeRejectsStringValue()
    {
        $m = new Matrix(array());
        $this->object->compute($m, 'foo');

    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Parameter is not scalar!
     * @dataProvider nonScalarValues
     */
    public function testComputeRejectsNonScalarValue($nonScalar)
    {
        $m = new Matrix(array());
        $this->object->compute($m, $nonScalar);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Matrix vertice (1,1) is not numeric!
     * @dataProvider nonScalarValues
     */
    public function testComputeRejectsNonScalarValueInMatrix($nonScalar)
    {
        $m = new Matrix(array($nonScalar));
        $this->object->compute($m, 1);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Matrix vertice (1,1) is not numeric
     */
    public function testComputeRejectsStringValueInMatrix()
    {
        $m = new Matrix(array('foo'));
        $this->object->compute($m, 1);
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
        $m = new Matrix($operand);
        $this->assertEquals($test, $this->object->compute($m, $scalar)->toArray());
    }

    public function computeMatrices()
    {
        return array(
            array(
                array(array(1,2,3), array(3,2,1), array(2,1,3)),
                array(array(0.5,1,1.5), array(1.5,1,0.5), array(1,0.5,1.5)),
                2
            ),
              array(
                array(array(1,2,3)),
                array(array(1/2.5,2/2.5,3/2.5)),
                2.5
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
                array(array(true,false)),
                array(array(1, 0)),
                true
            ),
        );
    }
}
