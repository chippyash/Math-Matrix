<?php
namespace Chippyash\Test\Math\Matrix\Derivative;
use Chippyash\Math\Matrix\Derivative\Determinant;
use Chippyash\Math\Matrix\NumericMatrix;
use Chippyash\Type\String\StringType;
use Chippyash\Type\RequiredType;

/**
 */
class DeterminantTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        RequiredType::getInstance()->set(RequiredType::TYPE_NATIVE);
        $this->object = new Determinant();
    }

    public function testSutHasDerivativeInterface()
    {
        $this->assertInstanceOf(
                'Chippyash\Math\Matrix\Interfaces\DerivativeInterface',
                $this->object);
    }

    /**
     * @expectedException Chippyash\Matrix\Exceptions\MatrixException
     * @expectedExceptionMessage No determinant for non-square matrix
     */
    public function testNonSquareMatrixThrowsException()
    {
        $mA = new NumericMatrix(array(1,2));
        $this->object->derive($mA);
    }

    public function testReturnsDeterminantForTwoByTwoSquareMatrixUsingLUMethod()
    {
        $obj = new Determinant(Determinant::METHOD_LU);
        $mA = new NumericMatrix(
               array(
                   array(1,2),
                   array(4,5)
               ));
        $this->assertEquals(-3, $obj->derive($mA)->get());
    }

    public function testReturnsDeterminantForTwoByTwoSquareMatrixUsingLaplaceMethod()
    {
        $obj = new Determinant(Determinant::METHOD_LAPLACE);
        $mA = new NumericMatrix(
               array(
                   array(1,2),
                   array(4,5)
               ));
        $this->assertEquals(-3, $obj->derive($mA)->get());
    }

    /**
     * @expectedException Chippyash\Math\Matrix\Exceptions\UndefinedComputationException
     * @expectedExceptionMessage Computation Error: Undefined computation: Unknown determinant computation method
     */
    public function testUndefinedComputationExceptionThrownForUnknownMethod()
    {
        $mA = new NumericMatrix(
               array(
                   array(1,2),
                   array(4,5)
               ));
        $obj = new Determinant(3);
        $obj->derive($mA);
    }

    /**
     * @dataProvider threeByThree
     * @link http://en.wikipedia.org/wiki/Matrix_determinant#3.C2.A0.C3.97.C2.A03_matrices
     * @link http://www.intmath.com/matrices-determinants/2-large-determinants.php
     */
    public function testReturnsDeterminantForThreeByThreeSquareMatrixUsingLUMethod($data, $determinant)
    {
        $obj = new Determinant(Determinant::METHOD_LU);
        $this->assertEquals($determinant, $obj->derive(new NumericMatrix($data))->get());
    }

    /**
     * @dataProvider threeByThree
     * @link http://en.wikipedia.org/wiki/Matrix_determinant#3.C2.A0.C3.97.C2.A03_matrices
     * @link http://www.intmath.com/matrices-determinants/2-large-determinants.php
     */
    public function testReturnsDeterminantForThreeByThreeSquareMatrixUsingLaplaceMethod($data, $determinant)
    {
        $obj = new Determinant(Determinant::METHOD_LAPLACE);
        $this->assertEquals($determinant, $obj->derive(new NumericMatrix($data))->get());
    }

    /**
     *
     * @return array [[matrix, determinant] ... ]
     */
    public function threeByThree()
    {
        return [
            [
                [[1,2,3],
                [4,5,6],
                [7,8,9]]
                , 0
            ],
            [
                [[12,2,3],
                [4,5,6],
                [7,8,9]]
                , -33
            ],
            [
                [[12,2,3],
                [4,-5,6],
                [7,8,9]]
                , -903
            ],
            [
                [[-2,3,-1],
                   [5,-1,4],
                   [4,-8,2]]
                , -6
            ],
            [
                [[3,-1,2],
                [6,2,5],
                [2,-7,1]]
                , 15
            ],
            [
                [[4,2,0],
                [6,2,5],
                [2,-7,1]]
                , 156
            ],
            [
                [[4,2,0],
                [3,-1,2],
                [2,-7,1]]
                , 54
            ],
            [
                [[4,2,0],
                [3,-1,2],
                [6,2,5]]
                , -42
            ],
            [
                [[6,11,9],
                [12,10,5],
                [13,2,14]]
                ,-1307
            ]
        ];
    }

    /**
     * @dataProvider bigMatrices
     */
    public function testReturnsDeterminantForNByNSquareMatrixUsingLUMethod($data, $determinant)
    {
       $obj = new Determinant(Determinant::METHOD_LU);
       $this->assertEquals($determinant, $obj->derive(new NumericMatrix($data))->get());
    }

    /**
     * @dataProvider bigMatrices
     */
    public function testReturnsDeterminantForNByNSquareMatrixUsingLaplaceMethod($data, $determinant)
    {
       $obj = new Determinant(Determinant::METHOD_LAPLACE);
       $this->assertEquals($determinant, $obj->derive(new NumericMatrix($data))->get());
    }

    public function bigMatrices()
    {
        return array(
            array(
                array(
                    array(7,4,2,0),
                    array(6,3,-1,2),
                    array(4,6,2,5),
                    array(8,2,-7,1),
                ), -279
            )
        );
    }

    /**
     * @expectedException Chippyash\Math\Matrix\Exceptions\UndefinedComputationException
     * @expectedExceptionMessage Computation Error: Undefined computation: No available strategy found to determine the determinant
     *
     * @runInSeparateProcess
     */
    public function testCanSetUpperLimitForLuMethodWhenAutoDeterminingStrategy()
    {
        $obj = new Determinant(); //uses auto method by default
        $obj->tune(new StringType('luLimit'), 2);
        $data = [[1,2,3],
                [4,5,6],
                [7,8,9]];
        $obj->derive(new NumericMatrix($data));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage foo is unknown for tuning
     */
    public function testTuningWithInvalidNameThrowsException()
    {
        $obj = new Determinant();
        $obj->tune(new StringType('foo'), 'bar');
    }
}
