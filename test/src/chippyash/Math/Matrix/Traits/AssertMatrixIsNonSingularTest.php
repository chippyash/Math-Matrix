<?php
namespace chippyash\Test\Math\Matrix\Traits;
use chippyash\Math\Matrix\Traits\AssertMatrixIsNonSingular;
use chippyash\Math\Matrix\Matrix;

class stubTraitAssertMatrixIsNonSingular
{
    use AssertMatrixIsNonSingular;

    public function test(Matrix $mA, $msg = null)
    {
        return (is_null($msg))
                ? $this->assertMatrixIsNonSingular($mA)
                : $this->assertMatrixIsNonSingular($mA, $msg);
    }
}

class AssertMatrixIsNonSingularTest extends \PHPUnit_Framework_TestCase
{
    protected $object;

    protected function setUp()
    {
        $this->object = new stubTraitAssertMatrixIsNonSingular();
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Matrix is non singular
     * @covers chippyash\Matrix\Traits\AssertMatrixIsNonSingular::assertMatrixIsNonSingular
     */
    public function testEmptyMatrixThrowsException()
    {
        $this->mB = new Matrix([]);

        $this->object->test(new Matrix([]));
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: foo
     * @covers chippyash\Matrix\Traits\AssertMatrixIsNonSingular::assertMatrixIsNonSingular
     */
    public function testEmptyMatrixThrowsExceptionWithUserMessage()
    {
        $this->object->test(new Matrix([]), 'foo');
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Matrix is non singular
     */
    public function testSingleItemMatrixThrowsException()
    {
        $this->object->test(new Matrix([0]));
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: foo
     */
    public function testSingleItemMatrixThrowsExceptionWithUserMessage()
    {
        $this->object->test(new Matrix([0]), 'foo');
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Matrix is non singular
     */
    public function testNonSquareMatrixThrowsException()
    {
        $this->object->test(new Matrix([0,1,2]));
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: foo
     */
    public function testNonSquareMatrixThrowsExceptionWithUserMessage()
    {
        $this->object->test(new Matrix([0,1,2]), 'foo');
    }

    /**
     * @covers chippyash\Matrix\Traits\AssertMatrixIsNonSingular::assertMatrixIsNonSingular
     */
    public function testNonSingularMatrixReturnsClass()
    {
        $this->assertInstanceOf(
                'chippyash\Test\Matrix\Traits\stubTraitAssertMatrixIsNonSingular',
                $this->object->test(new Matrix([[12,2,3],[4,5,6],[7,8,9]])));
    }

    /**
     * @expectedException chippyash\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Matrix is non singular
     * @covers chippyash\Matrix\Traits\AssertMatrixIsNonSingular::assertMatrixIsNonSingular
     * @dataProvider singularMatrices
     */
    public function testSingularMatrixThrowsException($singular)
    {
        $this->object->test(new Matrix($singular));
    }

    public function singularMatrices()
    {
        return [
            [[[0,0],[0,0]]],
            [[[0,0],[0,1]]],
            [[[0,0],[1,0]]],
            [[[0,0],[1,1]]],
            [[[0,1],[0,0]]],
            [[[0,1],[0,1]]],
            [[[1,0],[0,0]]],
            [[[1,0],[1,0]]],
            [[[1,1],[0,0]]],
            [[[1,1],[1,1]]],
            [[[1,2,3],[4,5,6],[7,8,9]]],
        ];
    }
}
