<?php
namespace chippyash\Test\Math\Matrix\Transformation;
use chippyash\Math\Matrix\Transformation\Invert;
use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Math\Matrix\RationalMatrix;
use chippyash\Type\Number\FloatType;
use chippyash\Type\Number\Rational\RationalTypeFactory;

/**
 * Test inversion by determinant
 *
 */
class InvertDeterminantTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new Invert(Invert::METHOD_DET);
    }

    public function testEmptyMatrixReturnsEmptyNumericMatrix()
    {
        $this->assertTrue($this->object->transform(new NumericMatrix([]))->is('empty'));
    }

    public function testSingleItemNoZeroMatrixReturnsSimpleInverseNumericMatrix()
    {
        $this->assertEquals([[new FloatType(0.5)]], $this->object->transform(new NumericMatrix([2]))->toArray());
    }

    /**
     * @expectedException chippyash\Math\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Division by zero
     */
    public function testSingleItemZeroMatrixThrowsException()
    {
        $this->object->transform(new NumericMatrix([0]));
    }

    /**
     * @expectedException chippyash\Math\Matrix\Exceptions\UndefinedComputationException
     * @expectedExceptionMessage Computation Error: Undefined computation: Unknown Inverse computation method
     */
    public function testUndefinedComputationExceptionThrownForUnknownMethod()
    {
        $obj = new Invert(1000);
    }

    /**
     * @expectedException chippyash\Math\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Can only perform inversion on non singular matrix
     */
    public function testComputeNonInvertibleMatricesThrowsException()
    {
        $mA = new NumericMatrix(array(array(1,-3),array(-2,6)));
        $this->object->transform($mA);
    }

    /**
     * @dataProvider computeMatrices
     *
     */
    public function testTransformWithNumericMatrixReturnsCorrectResult($operand, $result)
    {
        $mA = new NumericMatrix($operand);
        $mI = $this->object->transform($mA);
        $test = $mI->toArray();
        foreach ($test as &$row) {
            foreach ($row as &$item) {
                $item = $item();

            }
        }
        foreach ($result as &$row) {
            foreach ($row as &$item) {
                $item = $item();

            }
        }
        $this->assertEquals($result, $test);
    }

    /**
     * The limitations of php (actually any common language) internal maths
     * mean that it is hard to produce A*Inv(A)=Identity using floating point maths.
     * This test proves that.
     *
     * testTransformWithRationalMatrixProducesIdentityMatrixWhenMultiplied
     * proves it can be done using rational numbers.
     *
     * This single test is the reason why I developed the RationalType into the
     * underlying strong-type library and why the ComplexType is based on
     * RationalTypes.
     *
     * @link http://en.wikipedia.org/wiki/Matrix_inverse
     * @dataProvider computeMatrices
     *
     * However this is not a stable test as it can sometimes assert true, sometimes false
     *
     */
//    public function testTransformWithNumericMatrixDoesNotProduceIdentityMatrixWhenMultiplied($operand)
//    {
//        $mA = new NumericMatrix($operand);
//        $mI = $this->object->transform($mA);
//        $result = $mA('Mul\Matrix', $mI);
//        $this->assertFalse($result->is('identity'));
//    }

    /**
     * This test is exactly the same as
     * testTransformWithNumericMatrixDoesNotProduceIdentityMatrixWhenMultiplied
     * except that  here, we use a rational matrix.
     *
     * @dataProvider computeMatrices
     */
    public function testTransformWithRationalMatrixProducesIdentityMatrixWhenMultiplied($operand)
    {
        $mA = new RationalMatrix($operand);
        $mI = $this->object->transform($mA);
        $result = $mA('Mul\Matrix', $mI);
        $this->assertTrue($result->is('identity'));
    }

    /**
     *
     * @return array[[operand, result],...]
     */
    public function computeMatrices()
    {
        return [
            [
                [
                    [6,11,9],
                    [12,10,5],
                    [13,2,14]],
                [
                    [new FloatType(-0.099464422341239),new FloatType(0.10405508798776),new FloatType(0.026778882938026)],
                    [new FloatType(0.078806426931905),new FloatType(0.025248661055853),new FloatType(-0.059678653404744)],
                    [new FloatType(0.081101759755164),new FloatType(-0.10022953328233),new FloatType(0.055087987758225)]],
            ],
        ];
    }

}
