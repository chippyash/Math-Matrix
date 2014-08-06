<?php
namespace chippyash\Test\Math\Matrix\Computation\Mul;
use chippyash\Math\Matrix\Computation\Mul\Scalar;
use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Type\Number\IntType;
use chippyash\Type\Number\FloatType;
use chippyash\Type\Number\Rational\RationalTypeFactory;

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
        $m = new NumericMatrix(array());
        $test = $this->object->compute($m, 1);
        $this->assertTrue($test->is('empty'));
    }

    public function testComputeAcceptsNumericScalarValue()
    {
        $m = new NumericMatrix([1]);
        $this->object->compute($m, 0);
        $this->object->compute($m, 1.23);
        $this->object->compute($m, true);
        $this->object->compute($m, '2/3');
    }

    /**
     * @expectedException chippyash\Math\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Scalar parameter is not a supported type for numeric matrices: string
     */
    public function testComputeRejectsNonNumericStringValue()
    {
        $m = new NumericMatrix([1]);
        $this->object->compute($m, 'foo');
    }

    /**
     * @expectedException chippyash\Math\Matrix\Exceptions\ComputationException
     * @dataProvider nonScalarValues
     */
    public function testComputeRejectsNonScalarValue($nonScalar)
    {
        $m = new NumericMatrix([1]);
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

    /**
     * @expectedException chippyash\Matrix\Exceptions\MatrixException
     * @expectedExceptionMessage The string representation of the number ('foo') is invalid for a rational
     */
    public function testComputeRejectsStringValueInMatrix()
    {
        $m = new NumericMatrix(array('foo'));
        $this->object->compute($m, 1);
    }

    public function nonScalarValues()
    {
        return [
            [[[]]],  //array
            [new \stdClass()], //object
            [tmpfile()]        //resource
        ];
    }


    /**
     * @dataProvider computeMatrices
     */
    public function testComputeReturnsCorrectResult($operand, $test, $scalar)
    {
        $m = new NumericMatrix($operand);
        $this->assertEquals($test, $this->object->compute($m, $scalar)->toArray());
    }

    public function computeMatrices()
    {
        return [
            [
                [[1,2,3], [3,2,1], [2,1,3]],
                [[new IntType(2),new IntType(4),new IntType(6)],
                 [new IntType(6),new IntType(4),new IntType(2)],
                 [new IntType(4),new IntType(2),new IntType(6)]],
                2
            ],
              [
                [[1,2,3]],
                [[RationalTypeFactory::create(2.5), RationalTypeFactory::create(5.0), RationalTypeFactory::create(7.5)]],
                2.5
            ],
              [
                [[1.5,2.5,3.5]],
                [[ RationalTypeFactory::create(3.0), RationalTypeFactory::create(5.0), RationalTypeFactory::create(7.0)]],
                2
            ],
              [
                [[1.12,2.12,3.12]],
                [[ RationalTypeFactory::create(1.12), RationalTypeFactory::create(2.12), RationalTypeFactory::create(3.12)]],
                1.0
            ],
            [
                [[1,2,3]],
                [[new IntType(1),new IntType(2),new IntType(3)]],
                true
            ],
            [
                [[1,2,3]],
                [[new IntType(0),new IntType(0),new IntType(0)]],
                false
            ],
            [
                [[true,false]],
                [[new IntType(1), new IntType(0)]],
                true
            ],
            [
                [[true,false]],
                [[new IntType(0), new IntType(0)]],
                false
            ],
        ];
    }
}
