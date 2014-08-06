<?php
namespace chippyash\Test\Math\Matrix\Transformation;
use chippyash\Math\Matrix\Transformation\Invert;
use chippyash\Math\Matrix\NumericMatrix;
use chippyash\Math\Matrix\RationalMatrix;
use chippyash\Type\Number\FloatType;

/**
 * Test inversion by Gauss-Jordan method
 *
 */
class InvertGaussJordanTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new Invert(Invert::METHOD_GJ);
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
     */
    public function testComputeWithNumericMatrixReturnsCorrectResult($operand, $result)
    {
        $mA = new NumericMatrix($operand);
        $mI = $this->object->transform($mA);
        echo $mI->setFormatter(new \chippyash\Matrix\Formatter\Ascii())->display();
        $this->assertEquals($result, $mI->toArray());
    }

    /**
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
