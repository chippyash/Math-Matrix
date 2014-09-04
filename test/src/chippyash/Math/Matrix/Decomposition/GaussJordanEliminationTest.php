<?php
namespace chippyash\Test\Math\Matrix\Decomposition;
use chippyash\Math\Matrix\Decomposition\GaussJordanElimination;
use chippyash\Math\Matrix\NumericMatrix;

class GaussJordanEliminationTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected $testNonSingular = [
                                    [-12,2,3],
                                    [4,5,6],
                                    [7,8,9]
                                 ];

    protected $testIdentity = [
                                    [1,0,0],
                                    [0,1,0],
                                    [0,0,1]
                                 ];

    protected function setUp()
    {
        $this->object = new GaussJordanElimination();
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\MatrixException
     * @expectedExceptionMessage Parameter extra is not a matrix
     */
    public function testDecomposeWithOneParameterThrowsException()
    {
        $this->object->decompose(new NumericMatrix($this->testNonSingular));
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\MatrixException
     * @expectedExceptionMessage Parameter extra is not a matrix
     */
    public function testDecomposeWithNonNumericMatrixExtraParameterThrowsException()
    {
        $this->object->decompose(new NumericMatrix($this->testNonSingular), $this->testNonSingular);
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\MatrixException
     * @expectedExceptionMessage Parameter mA is not a square matrix
     */
    public function testDecomposeWithNonSquareFirstParameterThrowsException()
    {
        $a = $this->testNonSingular;
        array_pop($a);
        $this->object->decompose(new NumericMatrix($a), new NumericMatrix($a));
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\MatrixException
     * @expectedExceptionMessage mA->rows != extra->rows
     */
    public function testDecomposeWithExtraMatrixNotHavingSameNumberOfRowsAsFirstMatrixThrowsException()
    {
        $a = $this->testNonSingular;
        array_pop($a);
        $this->object->decompose(new NumericMatrix($this->testNonSingular), new NumericMatrix($a));
    }

    /**
     * @expectedException chippyash\Math\Matrix\Exceptions\SingularMatrixException
     * @expectedExceptionMessage GaussJordanElimination
     */
    public function testDecomposeWithSingularFirstMatrixThrowsException()
    {
        $this->object->decompose(new NumericMatrix([[0,0],[0,0]]), new NumericMatrix([[0],[0]]));
    }

    public function testDecomposeWithNonSingularFirstMatrixReturnsDecomposition()
    {
        $ret = $this->object->decompose(
                new NumericMatrix($this->testNonSingular),
                new NumericMatrix($this->testIdentity));
        $this->assertInstanceOf('chippyash\Math\Matrix\Decomposition\GaussJordanElimination', $ret);
    }

    public function testDecomposeCanSolveLinearEquation()
    {
        //solve
        //x + y + z = 5
        //2x + 3y + 5z = 8
        //4x + 5z = 2

        $mA = new NumericMatrix(
                [[1, 1, 1],
                 [2, 3, 5],
                 [4, 0, 5]]
                );
        $mB = new NumericMatrix(
                [[5],
                 [8],
                 [2]]
                );
        $ret = $this->object->decompose($mA, $mB);
        $expected = new NumericMatrix([
                [3], //x
                [4], //y
                [-2]]);//z
        $this->assertTrue($expected->equality($ret->right, false));
    }
}
