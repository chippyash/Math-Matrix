<?php
namespace chippyash\Test\Math\Matrix\Derivative;
use chippyash\Math\Matrix\Derivative\Determinant;
use chippyash\Math\Matrix\NumericMatrix;

/**
 */
class DeterminantTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new Determinant();
    }

    public function testSutHasDerivativeInterface()
    {
        $this->assertInstanceOf(
                'chippyash\Math\Matrix\Interfaces\DerivativeInterface',
                $this->object);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\MatrixException
     * @expectedExceptionMessage No determinant for empty matrix
     */
    public function testEmptyMatrixThrowsException()
    {
        $mA = new NumericMatrix(array());
        $this->object->derive($mA);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\MatrixException
     * @expectedExceptionMessage No determinant for non-square matrix
     */
    public function testNonSquareMatrixThrowsException()
    {
        $mA = new NumericMatrix(array(1,2));
        $this->object->derive($mA);
    }

    /**
     *
     * @todo put back in once figured out what is wrong with LU determinant
     */
//    public function testReturnsDeterminantForTwoByTwoSquareMatrixUsingLUMethod()
//    {
//        $obj = new Determinant(Determinant::METHOD_LU);
//        $mA = new NumericMatrix(
//               array(
//                   array(1,2),
//                   array(4,5)
//               ));
//        $this->assertEquals(-3, $obj->derive($mA));
//    }

    public function testReturnsDeterminantForTwoByTwoSquareMatrixUsingInternalMethod()
    {
        $obj = new Determinant(Determinant::METHOD_INTERNAL);
        $mA = new NumericMatrix(
               array(
                   array(1,2),
                   array(4,5)
               ));
        $this->assertEquals(-3, $obj->derive($mA)->get());
    }

    /**
     * @expectedException chippyash\Math\Matrix\Exceptions\UndefinedComputationException
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
     *
     * @todo put back in once figured out what is wrong with LU determinant
     */
//    public function testReturnsDeterminantForThreeByThreeSquareMatrixUsingLUMethod($data, $determinant)
//    {
//        $obj = new Determinant(Determinant::METHOD_LU);
//        $this->assertEquals($determinant, $obj->derive(new NumericMatrix($data)));
//    }

    /**
     * @dataProvider threeByThree
     * @link http://en.wikipedia.org/wiki/Matrix_determinant#3.C2.A0.C3.97.C2.A03_matrices
     * @link http://www.intmath.com/matrices-determinants/2-large-determinants.php
     */
    public function testReturnsDeterminantForThreeByThreeSquareMatrixUsingInternalMethod($data, $determinant)
    {
        $obj = new Determinant(Determinant::METHOD_INTERNAL);
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

//    /**
//     * @dataProvider bigMatrices
//     */
//    public function testReturnsDeterminantForNByNSquareMatrixUsingLUMethod($data, $determinant)
//    {
//       $this->assertEquals($determinant, $this->object->derive(new NumericMatrix($data))->get());
//    }

    /**
     * @dataProvider bigMatrices
     */
    public function testReturnsDeterminantForNByNSquareMatrixUsingInternalMethod($data, $determinant)
    {
        $obj = new Determinant(Determinant::METHOD_INTERNAL);
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
}
