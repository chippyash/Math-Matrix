<?php
namespace chippyash\Test\Math\Matrix\Traits;
use chippyash\Math\Matrix\Traits\AssertMatrixIsNonSingular;
use chippyash\Math\Matrix\NumericMatrix;

class stubTraitAssertMatrixIsNonSingular
{
    use AssertMatrixIsNonSingular;

    public function test(NumericMatrix $mA, $msg = null)
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

    public function testNonSingularMatrixReturnsClass()
    {
        $this->assertInstanceOf(
                'chippyash\Test\Math\Matrix\Traits\stubTraitAssertMatrixIsNonSingular',
                $this->object->test(new NumericMatrix([[12,2,3],[4,5,6],[7,8,9]])));
    }

    /**
     * @expectedException chippyash\Math\Matrix\Exceptions\ComputationException
     * @expectedExceptionMessage Computation Error: Matrix is non singular
     * @dataProvider singularMatrices
     */
    public function testSingularMatrixThrowsException($singular)
    {
        $this->object->test(new NumericMatrix($singular));
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
