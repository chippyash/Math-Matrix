<?php
namespace chippyash\Test\Math\Matrix\Computation;
use chippyash\Math\Matrix\Computation\Mul\Matrix as Mult;
use chippyash\Math\Matrix\Computation\Add\Matrix as Add;
use chippyash\Math\Matrix\Computation\Mul\Scalar;
use chippyash\Matrix\Transformation\Transpose;
use chippyash\Math\Matrix\Derivative\Trace;
use chippyash\Math\Matrix\NumericMatrix as Matrix;
use chippyash\Math\Matrix\IdentityMatrix;
use chippyash\Type\Number\IntType;

/**
 * Unit tests to check that matrix multiplication properties are maintained
 * by the matrix mult functionality
 *
 */
class MatrixMultiplicationPropertiesTest extends \PHPUnit_Framework_TestCase
{
    protected $object;
    protected $empty;
    protected $rowVector;
    protected $colVector;
    protected $square;
    protected $single;
    protected $wideRectangle;
    protected $longRectangle;

    public function setUp()
    {
        $this->object = new Mult();
        $this->empty = new Matrix(array());
        $this->rowVector = new Matrix(
                array(
            array(1, 2, 3)));
        $this->colVector = new Matrix(
                array(
            array(1),
            array(2),
            array(3)));
        $this->square = new Matrix(
                array(
            array(1, 2, 3),
            array(1, 2, 3),
            array(1, 2, 3)));
        $this->single = new Matrix(array(1));
        $this->wideRectangle = new Matrix(
                array(
            array(1, 2, 3),
            array(1, 2, 3)));
        $this->longRectangle = new Matrix(
                array(
            array(1, 2),
            array(1, 2),
            array(1, 2)));
    }

    /**
     * General non commutative rule
     */
    public function testMatrixMultiplicationIsNotCommutative()
    {
        $mA = $this->colVector;
        $mB = $this->rowVector;
        $this->assertNotEquals(
                $this->object->compute($mA, $mB),
                $this->object->compute($mB, $mA)
                );

        $mAA = $this->square;
        $mBB = $mAA("Transpose"); //make sure we have a different matrix
        $this->assertNotEquals(
                $this->object->compute($mAA, $mBB),
                $this->object->compute($mBB, $mAA)
                );
    }

    /**
     * Exception to non commutative rule
     * Empty matrix is special form of index matrix
     */
    public function testMultiplicationByAnEmptyMatrixIsCommmutative()
    {
        $mA = $this->empty;
        $mB = $this->square;
        $this->assertEquals(
                $this->object->compute($mA, $mB),
                $this->object->compute($mB, $mA)
                );
    }

    /**
     * Exception to non commutative rule
     */
    public function testMultiplicationByAnIdentityMatrixIsCommmutative()
    {
        $mA = $this->square;
        $mB = new IdentityMatrix(new IntType($mA->rows()));
        $this->assertEquals(
                $this->object->compute($mA, $mB),
                $this->object->compute($mB, $mA)
                );
    }

    /**
     * Exception to non commutative rule
     * Single item matrix is special form of index matrix
     */
    public function testMultiplicationByASingleItemMatrixIsCommmutative()
    {
        $mA = $this->single;
        $mB = $this->single;
        $this->assertEquals(
                $this->object->compute($mA, $mB),
                $this->object->compute($mB, $mA)
                );
    }

    /**
     * Exception to non commutative rule
     */
    public function testMultiplicationByTwoSquareMatricesWithTheSameVerticesIsCommutative()
    {
        $mA = $this->square;
        $mB = $this->square;
        $this->assertEquals(
                $this->object->compute($mA, $mB),
                $this->object->compute($mB, $mA)
                );
    }

    /**
     * Multiplication is Distributive over matrix addition
     * Left distributivity
     * A(B + C) = AB + AC
     */
    public function testMultiplicationMaintainsLeftDistributivityOverMatrixAddition()
    {
        //create three different matrices
        $mA = $this->square;
        $mB = $mA('Transpose');
        $mC = $mA('Mul\\Scalar',5);

        $fAdd = new Add();
        $fMul = $this->object;

        $this->assertEquals(
                $fMul($mA,$fAdd($mB, $mC)),
                $fAdd($fMul($mA, $mB),$fMul($mA, $mC))
                );
    }

    /**
     * Multiplication is Distributive over matrix addition
     * Right distributivity
     * (A+B)C = AC + BC
     */
    public function testMultiplicationMaintainsRightDistributivityOverMatrixAddition()
    {
        //create three different matrices
        $mA = $this->square;
        $mB = $mA('Transpose');
        $mC = $mA('Mul\\Scalar',5);

        $fAdd = new Add();
        $fMul = $this->object;

        $this->assertEquals(
                $fMul($fAdd($mA, $mB), $mC),
                $fAdd($fMul($mA, $mC),$fMul($mB, $mC))
                );
    }

    /**
     * Scalar multiplication is compatible with matrix multiplication
     * s(AB) = (sA)B and (AB)s = A(Bs)
     */
    public function testScalarMultiplicationIsCompatibleWithMatrixMultiplication()
    {
        //create two different matrices
        $mA = $this->square;
        $mB = $mA('Transpose');
        $s = 2.23;

        $fS = new Scalar();
        $fM = $this->object;

        $this->assertEquals(
                $fS($fM($mA, $mB), $s),
                $fM($fS($mA, $s), $mB)
                );
        $this->assertEquals(
                $fS($fM($mA, $mB), $s),
                $fM($mA, $fS($mB, $s))
                );
    }

    /**
     * Transpose
     * fT(AB) = fT(A).$fT(B)
     */
    public function testTranspositionIsCommutative()
    {
        //create two different matrices
        $mA = $this->square;
        $mB = $mA('Mul\\Scalar',3.76);

        $fT = new Transpose();
        $fM = $this->object;

        $this->assertEquals(
                $fT($fM($mA, $mB)),
                $fM($fT($mA), $fT($mB))
                );
    }

    /**
     * Trace - square matrices only
     * tr(AB) = tr(BA)
     */
    public function testTraceIsCommutative()
    {
        //create two different matrices
        $mA = $this->square;
        $mB = $mA('Mul\\Scalar',3.76);

        $fTR = new Trace();
        $fM = $this->object;

        $this->assertEquals(
                $fTR($fM($mA, $mB)),
                $fTR($fM($mB, $mA))
                );
    }

}
